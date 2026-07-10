<?php

// ============================================================
// فایل: database/seeders/CategorySeeder.php
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'آنتی‌بیوتیک',       'slug' => 'antibiotic',       'description' => 'داروهای ضد باکتری و عفونت'],
            ['name' => 'قلب و عروق',          'slug' => 'cardiovascular',   'description' => 'داروهای فشار خون، کلسترول و ریتم قلب'],
            ['name' => 'دیابت',               'slug' => 'diabetes',         'description' => 'داروهای کنترل قند خون'],
            ['name' => 'دستگاه گوارش',        'slug' => 'gastrointestinal', 'description' => 'داروهای معده، روده و کبد'],
            ['name' => 'مسکن و ضدالتهاب',    'slug' => 'analgesic',        'description' => 'داروهای تسکین درد و التهاب'],
            ['name' => 'ویتامین و مکمل',      'slug' => 'vitamin',          'description' => 'مکمل‌های غذایی و ویتامین‌ها'],
            ['name' => 'اعصاب و روان',        'slug' => 'neurological',     'description' => 'داروهای اضطراب، افسردگی و صرع'],
            ['name' => 'تنفسی',               'slug' => 'respiratory',      'description' => 'داروهای آسم، سرماخوردگی و آلرژی'],
            ['name' => 'پوست و مو',           'slug' => 'dermatology',      'description' => 'کرم‌ها و داروهای پوستی'],
            ['name' => 'چشم و گوش',           'slug' => 'ent',              'description' => 'قطره‌ها و داروهای چشم و گوش'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true])
            );
        }

        $this->command->info('✅ دسته‌بندی‌ها ساخته شدند.');
    }
}
