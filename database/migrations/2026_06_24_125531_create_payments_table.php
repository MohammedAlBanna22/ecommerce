<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
                    // العلاقة مع الطلب
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete(); // إذا حُذف الطلب → يُحذف الدفع

            // بيانات الدفع
            $table->decimal('amount', 10, 2); // المبلغ (50.00)
            $table->string('currency', 3)->default('USD'); // العملة (USD, JOD, SAR)

            // طريقة الدفع
            $table->enum('method', ['stripe', 'paypal', 'cod', 'bank_transfer']);

            // معرف المعاملة من الـ Gateway
            $table->string('transaction_id')->nullable();
            // مثال: pi_3MmBw2lrZ9MpqBv4NEQYJq7 (من Stripe)

            // حالة الدفع
            $table->enum('status', [
                'pending',      // في الانتظار
                'completed',    // مكتمل ✓
                'failed',       // فاشل ✗
                'refunded',     // مسترد ←
                'partially_refunded' // مسترد جزئياً
            ])->default('pending');

            // استجابة الـ Gateway (JSON كامل - مهم لتصحيح الأخطاء!)
            $table->json('gateway_response')->nullable();
            /*
            مثال على محتواه:
            {
                "id": "pi_3MmBw2lrZ9MpqBv4NEQYJq7",
                "object": "payment_intent",
                "amount": 5000,
                "status": "succeeded",
                ...
            }
            */

            // تواريخ مهمة
            $table->timestamp('paid_at')->nullable(); // متى تم الدفع؟

            $table->timestamps(); // created_at, updated_at

            // Indexes للبحث السريع
            $table->index('transaction_id'); // للبحث عن دفع محدد
            $table->index('status');         // لعرض الدفعات حسب الحالة
            $table->index('created_at');     // للأرشيف
        });

        // تعليق توضيحي للجدول
        Schema::table('payments', function (Blueprint $table) {
            $table->comment('Stores all payment transactions for orders');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
