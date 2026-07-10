<?php

// ============================================================
// فایل: database/seeders/DatabaseSeeder.php
// دستور اجرا: php artisan db:seed
// یا از صفر: php artisan migrate:fresh --seed
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Hash, DB};
use Spatie\Permission\Models\{Role, Permission};
use App\Models\{User, Category, Medicine, Pharmacy, Order, OrderItem,
    StockMovement, Invoice, Payment};
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // غیرفعال کردن foreign key checks برای truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('🌱 شروع Seed...');

        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            MedicineSeeder::class,
            PharmacySeeder::class,
            OrderSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ همه داده‌ها با موفقیت وارد شدند!');
        $this->command->table(
            ['نقش', 'ایمیل', 'رمز عبور'],
            [
                ['ادمین',       'admin@pharma.ir',       'admin123456'],
                ['توزیع‌کننده', 'dist@pharma.ir',        'dist123456'],
                ['داروخانه',    'pharmacy@pharma.ir',    'pharmacy123'],
            ]
        );
    }
}

