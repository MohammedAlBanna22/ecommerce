<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    //
    /**
     * ============================================
     * عرض صفحة الدفع (Payment Page)
     * ============================================
     *
     * هذه الصفحة تظهر فقط للبطاقات الائتمانية
     * COD و PayPal يتم معالجتهم مباشرة من Checkout
     */
    public function show(Order $order)
    {
          abort_unless($order->user_id === auth()->id(), 403);

    $validStatuses = ['pending', 'unpaid', 'pending_payment'];

    if (!in_array($order->payment_status, $validStatuses)) {
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'This order is already paid!');
        }
        abort(404);
    }

    if ($order->created_at->addMinutes(15)->isPast()) {
        $order->update(['payment_status' => 'unpaid']);
        return redirect()->route('checkout.index')
            ->with('error', 'Order expired. Please try again.');
    }

    // ✅ التحقق من طريقة الدفع
    if (!in_array($order->payment_method, ['credit_card', 'stripe', 'paypal'])) {
        return redirect()->route('checkout.index')
            ->with('error', 'Invalid payment method for this page.');
    }

    $payment = $order->payments()->where('status', 'pending')->first();
    if (!$payment) {
        $payment = Payment::createPending($order, $order->payment_method);
    }

    return view('payment.show', compact('order', 'payment'));
    }

    /**
     * ============================================
     * معالجة دفع البطاقة (Stripe)
     * ============================================
     *
     * هذه الدالة تُستدعى من JavaScript (AJAX/Fetch)
     */
   public function processStripe(Request $request, Order $order)
{
    try {
        $validated = $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        abort_unless($order->user_id === auth()->id(), 403);

        if (!in_array($order->payment_status, ['pending', 'unpaid'])) {
            throw new \Exception('Order already processed');
        }

        DB::beginTransaction();

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // استرجاع أو إنشاء Payment Intent
            if ($order->transaction_id && str_starts_with($order->transaction_id, 'pi_')) {
                $paymentIntent = PaymentIntent::retrieve($order->transaction_id);
            } else {
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) ($order->total_price * 100),
                    'currency' => strtolower($order->currency ?? 'usd'),
                    'metadata' => [
                        'order_id' => $order->id,
                        'user_id' => auth()->id(),
                    ],
                    'description' => "Order #{$order->id} - " . auth()->user()->name,
                ]);

                $order->update(['transaction_id' => $paymentIntent->id]);
            }

            // تأكيد الدفع
          $paymentIntent->confirm([
    'payment_method' => $validated['payment_method_id'],
    'return_url' => route('payment.success', $order) . '?pi=' . $paymentIntent->id,
]);

            if ($paymentIntent->status === 'succeeded') {

                // ✅ تحديث الطلب (بدون paid_at)
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing',
                ]);

                // ✅ تحديث سجل الدفع (paid_at هنا صح لأنه في payments)
                $paymentRecord = $order->payments()
                    ->where('status', 'pending')
                    ->first() ?? new Payment();

                $paymentRecord->fill([
                    'order_id' => $order->id,
                    'amount' => $order->total_price,
                    'currency' => $order->currency ?? 'USD',
                    'method' => 'stripe',
                    'transaction_id' => $paymentIntent->id,
                    'status' => 'completed',
                    'gateway_response' => $paymentIntent->toArray(),
                    'paid_at' => now(), // ✅ هنا صح
                ]);
                $paymentRecord->save();

                // تأكيد المخزون
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product && $product->quantity >= $item->quantity) {
                        $product->decrement('quantity', $item->quantity);
                    }
                }

                // تفريغ السلة
                auth()->user()->cart?->items()?->delete();

                // تاريخ الطلب
                $order->histories()->create([
                    'status' => 'processing',
                    'notes' => 'Payment completed via Stripe'
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful!',
                    'redirect_url' => route('orders.show', $order),
                ]);

            } else {
                throw new \Exception('Payment not completed. Status: ' . $paymentIntent->status);
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollBack();
            Log::error('Stripe Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }

    } catch (\Exception $e) {
        Log::error('Payment Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage() ?: 'An error occurred.',
        ], 500);
    }
}

    /**
     * ============================================
     * صفحة النجاح بعد الدفع
     * ============================================
     */
    public function success(Request $request, Order $order)
    {
        // التحقق من الطلب
        abort_unless($order->user_id === auth()->id(), 403);

        // التحقق من الدفع
        if (!$order->isPaid()) {
            // قد يكون Webhook متأخراً، نتحقق يدوياً
            if ($request->has('pi')) {
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    $pi = PaymentIntent::retrieve($request->pi);

                    if ($pi->status === 'succeeded') {
                        $order->markAsPaid($pi->id);
                    }
                } catch (\Exception $e) {
                    // تجاهل
                }
            }
        }

        return view('payment.success', compact('order'));
    }

    /**
     * ============================================
     * إلغاء الدفع / الرجوع
     * ============================================
     */
    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        // خيارات عند الإلغاء:
        // 1. الاحتفاظ بالطلب (يمكن المحاولة مرة ثانية) ← سنستخدم هذا
        // 2. حذف الطلب وإرجاع المنتجات للمخزون

        return redirect()
            ->route('payment.show', $order)
            ->with('info', 'Payment cancelled. You can try again.');
    }

    /**
     * ============================================
     * Cash on Delivery Handler
     * ============================================
     *
     * تُستدعى عندما المستخدم يختار COD في Checkout
     */
    public function processCOD(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            // 1. تحديث الطلب
            $order->update([
                'payment_method' => 'cod',
                'payment_status' => 'unpaid', // غير مدفوع - عند الاستلام
                'order_status' => 'pending',
            ]);

            // 2. إنشاء سجل دفع COD
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'currency' => $order->currency ?? 'USD',
                'method' => 'cod',
                'transaction_id' => null, // لا يوجد transaction ID
                'status' => 'pending', // في انتظار الدفع عند الاستلام
                'gateway_response' => ['method' => 'cash_on_delivery'],
            ]);

            // 3. حجز المخزون (Reserved)
            foreach ($order->items as $item) {
                $item->product->increment('reserved_quantity', $item->quantity);
            }

            // 4. تفريغ السلة
            auth()->user()->cart?->items()?->delete();

            // 5. تاريخ
            $order->histories()->create([
                'status' => 'pending',
                'notes' => 'Order created - Cash on Delivery'
            ]);

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', "Order #{$order->id} created successfully! Pay when you receive it.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error creating COD order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ============================================
     * PayPal Handler (بسيط - لاحقاً)
     * ============================================
     */
    public function processPayPal(Request $request, Order $order)
    {
        // سيتم تنفيذها لاحقاً مع PayPal SDK
        // حالياً: ننشئ سجل ونحول لصفحة نجاح

        Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'method' => 'paypal',
            'status' => 'completed', // مؤقتاً
            'transaction_id' => $request->paymentId ?? 'paypal_test',
        ]);

        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'PayPal payment completed!');
    }
}
