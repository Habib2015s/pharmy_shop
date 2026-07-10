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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['in', 'out', 'adjustment', 'return']); // ورود/خروج/تعدیل/مرجوعی
            $table->integer('quantity');
            $table->integer('stock_before');             // موجودی قبل از عملیات
            $table->integer('stock_after');              // موجودی بعد از عملیات
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->nullable();     // شماره رفرنس
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
