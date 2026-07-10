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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // حساب کاربری
            $table->string('name');                      // نام داروخانه
            $table->string('owner_name');               // نام مالک
            $table->string('license_number')->unique(); // شماره پروانه
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('province');                 // استان
            $table->string('city');                     // شهر
            $table->text('address');
            $table->decimal('credit_limit', 15, 2)->default(0);   // سقف اعتبار
            $table->decimal('current_balance', 15, 2)->default(0); // مانده حساب
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
