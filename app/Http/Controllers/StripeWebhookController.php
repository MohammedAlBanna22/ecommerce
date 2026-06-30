<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    //
     public function handleWebhook(Request $request)
    {
        // 1. التحقق من توقيع Stripe (أمان)
        $endpoint_secret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid Payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid Signature', 400);
        }

        // 2. معالجة الحدث
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $this->handleSuccessfulPayment($paymentIntent);
        }

        return response('Webhook Handled', 200);
    }

    private function handleSuccessfulPayment($paymentIntent)
    {
        // البحث عن الطلب عن طريق الـ metadata أو transaction_id
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if (!$orderId) {
            // محاولة البحث عن طريق الـ PI ID في جدول الطلبات
            $order = Order::where('transaction_id', $paymentIntent->id)->first();
        } else {
            $order = Order::find($orderId);
        }

        if (!$order || $order->payment_status === 'paid') {
            return; // الطلب غير موجود أو مدفوع مسبقاً
        }

        // تحديث حالة الطلب (مثل ما فعلنا في processStripe)
        \DB::transaction(function () use ($order, $paymentIntent) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);

            $payment = $order->payments()->where('status', 'pending')->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $paymentIntent->id,
                    'paid_at' => now(),
                    'gateway_response' => $paymentIntent->toArray(),
                ]);
            }
        });
    }
}
