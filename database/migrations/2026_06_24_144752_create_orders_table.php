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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();    // شماره سفارش: ORD-20240101-0001
            $table->foreignId('pharmacy_id')->constrained();
            $table->foreignId('user_id')->constrained(); // کاربر ثبت‌کننده
            $table->enum('status', [
                'pending',      // در انتظار بررسی
                'confirmed',    // تأیید شده توسط ادمین
                'processing',   // در حال آماده‌سازی در انبار
                'dispatched',   // ارسال شده به راننده
                'delivered',    // تحویل داده شده
                'cancelled'     // لغو شده
            ])->default('pending');
            $table->decimal('total_amount', 15, 2)->default(0);   // مجموع
            $table->decimal('discount', 15, 2)->default(0);        // تخفیف
            $table->decimal('tax', 15, 2)->default(0);             // مالیات
            $table->decimal('final_amount', 15, 2)->default(0);    // مبلغ نهایی
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'transfer', 'credit', 'cheque'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
