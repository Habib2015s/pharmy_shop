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
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('pharmacy_id')->constrained();
            $table->foreignId('user_id')->constrained(); // کاربر ثبت‌کننده
            $table->decimal('amount', 15, 2);
            $table->enum('method', ['cash', 'transfer', 'cheque', 'pos']);
            $table->string('reference')->nullable();     // شماره پیگیری
            $table->date('payment_date');
            $table->text('note')->nullable();
            $table->timestamps();
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
