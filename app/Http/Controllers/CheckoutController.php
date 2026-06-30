<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Payment;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $cart = Cart::with(['items.product.mainImage'])
            ->where('user_id', auth()->id())
            ->first();

        $addresses = Address::where('user_id', auth()->id())
            ->latest()
            ->get();

        $defaultAddress = Address::where('user_id', auth()->id())
            ->where('is_default', true)
            ->first();

        return view('checkout.index', compact(
            'cart',
            'addresses',
            'defaultAddress'
        ));
    }

    /**
     * Process checkout and create order
     */
    public function store(Request $request, OrderService $orderService)
    {
        // ✅ 1. Validation (قبل أي شيء!)
        $validated = $request->validate([
            // العنوان: إما محفوظ أو جديد
            'address_id' => 'nullable|exists:addresses,id,user_id,' . auth()->id(),

            // عنوان جديد (مطلوب إذا ما اختار محفوظ)
            // 'new_address.full_name' => 'required_without:address_id|string|max:255',
            // 'new_address.phone' => 'required_without:address_id|string|max:20',
            // 'new_address.city' => 'required_without:address_id|string|max:255',
            // 'new_address.area' => 'required_without:address_id|string|max:255',
            // 'new_address.street' => 'required_without:address_id|string|max:255',
            // 'new_address.details' => 'nullable|string',
            // 'new_address.is_default' => 'sometimes|boolean',

            // طريقة الدفع
            'payment_method' => 'required|in:credit_card,paypal,cod',

            // اختياري
            'coupon_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ], [
                'address_id.required_without' => 'Please select a saved address or enter a new one',
                'new_address.full_name.required_without' => 'Full name is required when adding a new address',
                'new_address.phone.required_without' => 'Phone number is required when adding a new address',
                'new_address.city.required_without' => 'City is required when adding a new address',
                'new_address.area.required_without' => 'Area is required when adding a new address',
                'new_address.street.required_without' => 'Street address is required when adding a new address',
                'payment_method.required' => 'Please select a payment method',
            ]);

        // جلب السلة
        $cart = Cart::with(['items.product'])->where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty. Add some products first!');
        }

        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | STEP 1: DETERMINE ADDRESS
            |--------------------------------------------------------------------------
            */
            if ($request->filled('address_id')) {
                // ✅ استخدام عنوان محفوظ
                $address = Address::findOrFail($request->address_id);

                // تأكد أنه يخص المستخدم
                if ($address->user_id !== auth()->id()) {
                    throw new \Exception('Invalid address selected');
                }

            } else {
                // ✅ إنشاء عنوان جديد
                $address = auth()->user()->addresses()->create([
                    'full_name' => $validated['new_address']['full_name'],
                    'phone' => $validated['new_address']['phone'],
                    'city' => $validated['new_address']['city'],
                    'area' => $validated['new_address']['area'],
                    'street' => $validated['new_address']['street'],
                    'details' => $validated['new_address']['details'] ?? null,
                    'is_default' => $validated['new_address']['is_default'] ?? false,
                ]);

                // إذا كان أول عنوان، اجعله افتراضي تلقائياً
                $userAddressCount = auth()->user()->addresses()->count();
                if ($userAddressCount === 1) {
                    $address->update(['is_default' => true]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 2: CREATE ORDER VIA SERVICE
            |--------------------------------------------------------------------------
            */
            $order = $orderService->createOrder(
                auth()->user(),
                $cart,
                $request,
                $address  // ✅ تمرير العنوان للـ Service
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 3: DECISION POINT - Payment Method
            |--------------------------------------------------------------------------
            */
            $paymentMethod = $validated['payment_method'];

            if ($paymentMethod === 'credit_card' || $paymentMethod === 'stripe') {

                // ✅ CASE A: Credit Card → Create Pending Payment + Redirect

                // إنشاء سجل دفع pending
                Payment::createPending($order, 'stripe'); // ← $order هنا Model Order وليس Service!

                DB::commit();

                // تحويل لصفحة الدفع
                return redirect()
                    ->route('payment.show', $order)
                    ->with('success', "Order #{$order->id} created! Please complete your payment.");

            } elseif ($paymentMethod === 'paypal') {

                // ✅ CASE B: PayPal → Create Pending + Redirect to PayPal (لاحقاً)
                Payment::createPending($order, 'paypal');

                DB::commit();

                // TODO: Implement PayPal redirect
                return redirect()
                    ->route('payment.paypal', $order)
                    ->with('info', 'Redirecting to PayPal...');

            } else {

                // ✅ CASE C: Cash on Delivery → Create & Mark as Unpaid

                // تحديث الطلب
                $order->update([
                    'payment_method' => 'cod',
                    'payment_status' => 'unpaid', // غير مدفوع - عند الاستلام
                    'order_status' => 'pending',
                ]);

                // إنشاء سجل دفع COD
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_price,
                    'currency' => $order->currency ?? 'USD',
                    'method' => 'cod',
                    'transaction_id' => null, // لا يوجد transaction ID
                    'status' => 'pending', // في انتظار الدفع عند الاستلام
                    'gateway_response' => ['method' => 'cash_on_delivery'],
                ]);

                // حجزء المخزون (Reserved)
                foreach ($order->items as $item) {
                    $item->product->increment('reserved_quantity', $item->quantity);
                }

                // تفريغ السلة
                $cart->items()->delete();

                DB::commit();

                // إضافة تاريخ
                $order->histories()->create([
                    'status' => 'pending',
                    'notes' => 'Order created - Cash on Delivery'
                ]);

                return redirect()
                    ->route('orders.show', $order)
                    ->with('success', "🎉 Order #{$order->id} created successfully! Pay when you receive it.");
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', $e->getMessage())
                ->withInput(); // ✅ إرجاع المدخلات لتعبيتها
        }
    }
}