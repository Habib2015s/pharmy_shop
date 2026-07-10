<?php

// ============================================================
// فایل: database/seeders/MedicineSeeder.php
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Medicine, Category, User, StockMovement};

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::pluck('id', 'slug');
        $adminId = User::role('admin')->first()->id;

        $medicines = [
            // ─── آنتی‌بیوتیک ────────────────────────────────
            ['category_slug'=>'antibiotic', 'name'=>'آموکسی‌سیلین ۵۰۰',     'generic_name'=>'Amoxicillin 500mg',        'barcode'=>'ABC001', 'unit'=>'عدد',   'purchase_price'=>8000,  'sale_price'=>12000, 'stock'=>500, 'min_stock'=>50,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'antibiotic', 'name'=>'آزیترومایسین ۲۵۰',     'generic_name'=>'Azithromycin 250mg',       'barcode'=>'ABC002', 'unit'=>'عدد',   'purchase_price'=>15000, 'sale_price'=>22000, 'stock'=>300, 'min_stock'=>30,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'antibiotic', 'name'=>'سفالکسین ۵۰۰',         'generic_name'=>'Cefalexin 500mg',          'barcode'=>'ABC003', 'unit'=>'عدد',   'purchase_price'=>6000,  'sale_price'=>9500,  'stock'=>400, 'min_stock'=>40,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'antibiotic', 'name'=>'مترونیدازول ۲۵۰',      'generic_name'=>'Metronidazole 250mg',      'barcode'=>'ABC004', 'unit'=>'عدد',   'purchase_price'=>3000,  'sale_price'=>5000,  'stock'=>600, 'min_stock'=>60,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'antibiotic', 'name'=>'کلاریترومایسین ۵۰۰',   'generic_name'=>'Clarithromycin 500mg',     'barcode'=>'ABC005', 'unit'=>'بسته',  'purchase_price'=>45000, 'sale_price'=>65000, 'stock'=>150, 'min_stock'=>20,  'manufacturer'=>'داروسازی رازی'],

            // ─── قلب و عروق ─────────────────────────────────
            ['category_slug'=>'cardiovascular', 'name'=>'آتورواستاتین ۲۰',  'generic_name'=>'Atorvastatin 20mg',        'barcode'=>'CRD001', 'unit'=>'عدد',   'purchase_price'=>15000, 'sale_price'=>22000, 'stock'=>350, 'min_stock'=>35,  'manufacturer'=>'داروسازی پارس'],
            ['category_slug'=>'cardiovascular', 'name'=>'آملودیپین ۵',       'generic_name'=>'Amlodipine 5mg',           'barcode'=>'CRD002', 'unit'=>'عدد',   'purchase_price'=>5000,  'sale_price'=>8000,  'stock'=>500, 'min_stock'=>50,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'cardiovascular', 'name'=>'متوپرولول ۵۰',     'generic_name'=>'Metoprolol 50mg',          'barcode'=>'CRD003', 'unit'=>'عدد',   'purchase_price'=>8000,  'sale_price'=>12500, 'stock'=>280, 'min_stock'=>30,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'cardiovascular', 'name'=>'لیزینوپریل ۵',     'generic_name'=>'Lisinopril 5mg',           'barcode'=>'CRD004', 'unit'=>'عدد',   'purchase_price'=>6000,  'sale_price'=>9000,  'stock'=>420, 'min_stock'=>40,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'cardiovascular', 'name'=>'وارفارین ۵',        'generic_name'=>'Warfarin 5mg',             'barcode'=>'CRD005', 'unit'=>'عدد',   'purchase_price'=>4000,  'sale_price'=>7000,  'stock'=>7,   'min_stock'=>25,  'manufacturer'=>'داروسازی پارس', 'requires_prescription'=>true],

            // ─── دیابت ──────────────────────────────────────
            ['category_slug'=>'diabetes', 'name'=>'متفورمین ۵۰۰',           'generic_name'=>'Metformin 500mg',          'barcode'=>'DIA001', 'unit'=>'عدد',   'purchase_price'=>3500,  'sale_price'=>6000,  'stock'=>600, 'min_stock'=>60,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'diabetes', 'name'=>'گلی‌بن‌کلامید ۵',        'generic_name'=>'Glibenclamide 5mg',        'barcode'=>'DIA002', 'unit'=>'عدد',   'purchase_price'=>4000,  'sale_price'=>7000,  'stock'=>400, 'min_stock'=>40,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'diabetes', 'name'=>'انسولین NPH',             'generic_name'=>'Insulin NPH 100IU/ml',     'barcode'=>'DIA003', 'unit'=>'ویال',  'purchase_price'=>35000, 'sale_price'=>55000, 'stock'=>120, 'min_stock'=>20,  'manufacturer'=>'داروسازی رازی', 'requires_prescription'=>true, 'expiry_date'=>'2026-06-01'],
            ['category_slug'=>'diabetes', 'name'=>'سیتاگلیپتین ۱۰۰',       'generic_name'=>'Sitagliptin 100mg',        'barcode'=>'DIA004', 'unit'=>'عدد',   'purchase_price'=>85000, 'sale_price'=>120000,'stock'=>80,  'min_stock'=>15,  'manufacturer'=>'داروسازی پارس'],

            // ─── گوارش ──────────────────────────────────────
            ['category_slug'=>'gastrointestinal', 'name'=>'امپرازول ۲۰',    'generic_name'=>'Omeprazole 20mg',          'barcode'=>'GAS001', 'unit'=>'عدد',   'purchase_price'=>3000,  'sale_price'=>5500,  'stock'=>700, 'min_stock'=>70,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'gastrointestinal', 'name'=>'رانیتیدین ۱۵۰',  'generic_name'=>'Ranitidine 150mg',         'barcode'=>'GAS002', 'unit'=>'عدد',   'purchase_price'=>2500,  'sale_price'=>4500,  'stock'=>500, 'min_stock'=>50,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'gastrointestinal', 'name'=>'پانتوپرازول ۴۰', 'generic_name'=>'Pantoprazole 40mg',        'barcode'=>'GAS003', 'unit'=>'عدد',   'purchase_price'=>12000, 'sale_price'=>18000, 'stock'=>300, 'min_stock'=>30,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'gastrointestinal', 'name'=>'بیزموت',          'generic_name'=>'Bismuth Subsalicylate',    'barcode'=>'GAS004', 'unit'=>'شیشه',  'purchase_price'=>18000, 'sale_price'=>28000, 'stock'=>150, 'min_stock'=>20,  'manufacturer'=>'داروسازی پارس'],

            // ─── مسکن ───────────────────────────────────────
            ['category_slug'=>'analgesic', 'name'=>'ایبوپروفن ۴۰۰',         'generic_name'=>'Ibuprofen 400mg',          'barcode'=>'ANL001', 'unit'=>'بسته',  'purchase_price'=>5000,  'sale_price'=>8000,  'stock'=>800, 'min_stock'=>80,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'analgesic', 'name'=>'استامینوفن ۵۰۰',        'generic_name'=>'Acetaminophen 500mg',      'barcode'=>'ANL002', 'unit'=>'بسته',  'purchase_price'=>2500,  'sale_price'=>4500,  'stock'=>1000,'min_stock'=>100, 'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'analgesic', 'name'=>'ناپروکسن ۵۰۰',         'generic_name'=>'Naproxen 500mg',           'barcode'=>'ANL003', 'unit'=>'عدد',   'purchase_price'=>6000,  'sale_price'=>9500,  'stock'=>400, 'min_stock'=>40,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'analgesic', 'name'=>'دیکلوفناک ۵۰',         'generic_name'=>'Diclofenac 50mg',          'barcode'=>'ANL004', 'unit'=>'عدد',   'purchase_price'=>4500,  'sale_price'=>7000,  'stock'=>500, 'min_stock'=>50,  'manufacturer'=>'داروسازی پارس'],
            ['category_slug'=>'analgesic', 'name'=>'کدئین ۳۰',              'generic_name'=>'Codeine 30mg',             'barcode'=>'ANL005', 'unit'=>'عدد',   'purchase_price'=>8000,  'sale_price'=>13000, 'stock'=>5,   'min_stock'=>20,  'manufacturer'=>'داروسازی ایران', 'requires_prescription'=>true],

            // ─── ویتامین ────────────────────────────────────
            ['category_slug'=>'vitamin', 'name'=>'ویتامین D3 50000',         'generic_name'=>'Vitamin D3 50000IU',       'barcode'=>'VIT001', 'unit'=>'عدد',   'purchase_price'=>12000, 'sale_price'=>18000, 'stock'=>600, 'min_stock'=>60,  'manufacturer'=>'داروسازی پارس'],
            ['category_slug'=>'vitamin', 'name'=>'ویتامین C 1000',           'generic_name'=>'Vitamin C 1000mg',         'barcode'=>'VIT002', 'unit'=>'عدد',   'purchase_price'=>8000,  'sale_price'=>13000, 'stock'=>700, 'min_stock'=>70,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'vitamin', 'name'=>'مولتی‌ویتامین بزرگسال',   'generic_name'=>'Multivitamin Adult',       'barcode'=>'VIT003', 'unit'=>'بسته',  'purchase_price'=>35000, 'sale_price'=>52000, 'stock'=>250, 'min_stock'=>30,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'vitamin', 'name'=>'امگا ۳ - ۱۰۰۰',          'generic_name'=>'Omega-3 1000mg',           'barcode'=>'VIT004', 'unit'=>'بسته',  'purchase_price'=>45000, 'sale_price'=>68000, 'stock'=>180, 'min_stock'=>20,  'manufacturer'=>'داروسازی پارس'],
            ['category_slug'=>'vitamin', 'name'=>'آهن + اسید فولیک',        'generic_name'=>'Iron + Folic Acid',        'barcode'=>'VIT005', 'unit'=>'عدد',   'purchase_price'=>4000,  'sale_price'=>7000,  'stock'=>500, 'min_stock'=>50,  'manufacturer'=>'داروسازی ایران'],
            ['category_slug'=>'vitamin', 'name'=>'کلسیم + D3',              'generic_name'=>'Calcium + Vitamin D3',     'barcode'=>'VIT006', 'unit'=>'عدد',   'purchase_price'=>10000, 'sale_price'=>16000, 'stock'=>9,   'min_stock'=>30,  'manufacturer'=>'لابراتوار البرز'],

            // ─── اعصاب ──────────────────────────────────────
            ['category_slug'=>'neurological', 'name'=>'سرترالین ۵۰',        'generic_name'=>'Sertraline 50mg',          'barcode'=>'NEU001', 'unit'=>'عدد',   'purchase_price'=>12000, 'sale_price'=>18000, 'stock'=>200, 'min_stock'=>25,  'manufacturer'=>'داروسازی رازی', 'requires_prescription'=>true],
            ['category_slug'=>'neurological', 'name'=>'آلپرازولام ۰.۵',     'generic_name'=>'Alprazolam 0.5mg',         'barcode'=>'NEU002', 'unit'=>'عدد',   'purchase_price'=>5000,  'sale_price'=>9000,  'stock'=>150, 'min_stock'=>20,  'manufacturer'=>'داروسازی پارس', 'requires_prescription'=>true],
            ['category_slug'=>'neurological', 'name'=>'فلوکستین ۲۰',        'generic_name'=>'Fluoxetine 20mg',          'barcode'=>'NEU003', 'unit'=>'عدد',   'purchase_price'=>8000,  'sale_price'=>13000, 'stock'=>180, 'min_stock'=>20,  'manufacturer'=>'داروسازی ایران', 'requires_prescription'=>true],

            // ─── تنفسی ──────────────────────────────────────
            ['category_slug'=>'respiratory', 'name'=>'سالبوتامول اسپری',    'generic_name'=>'Salbutamol Inhaler',       'barcode'=>'RSP001', 'unit'=>'عدد',   'purchase_price'=>25000, 'sale_price'=>38000, 'stock'=>200, 'min_stock'=>25,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'respiratory', 'name'=>'فلوتیکازون اسپری',    'generic_name'=>'Fluticasone Inhaler',      'barcode'=>'RSP002', 'unit'=>'عدد',   'purchase_price'=>55000, 'sale_price'=>82000, 'stock'=>120, 'min_stock'=>15,  'manufacturer'=>'داروسازی پارس'],
            ['category_slug'=>'respiratory', 'name'=>'سیتریزین ۱۰',         'generic_name'=>'Cetirizine 10mg',          'barcode'=>'RSP003', 'unit'=>'عدد',   'purchase_price'=>3000,  'sale_price'=>5500,  'stock'=>600, 'min_stock'=>60,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'respiratory', 'name'=>'لوراتادین ۱۰',        'generic_name'=>'Loratadine 10mg',          'barcode'=>'RSP004', 'unit'=>'بسته',  'purchase_price'=>8000,  'sale_price'=>13000, 'stock'=>400, 'min_stock'=>40,  'manufacturer'=>'داروسازی ایران'],

            // ─── پوست ───────────────────────────────────────
            ['category_slug'=>'dermatology', 'name'=>'کرم هیدروکورتیزون',   'generic_name'=>'Hydrocortisone Cream 1%', 'barcode'=>'DRM001', 'unit'=>'عدد',   'purchase_price'=>15000, 'sale_price'=>24000, 'stock'=>180, 'min_stock'=>20,  'manufacturer'=>'داروسازی رازی'],
            ['category_slug'=>'dermatology', 'name'=>'کرم بتامتازون',        'generic_name'=>'Betamethasone Cream',     'barcode'=>'DRM002', 'unit'=>'عدد',   'purchase_price'=>12000, 'sale_price'=>19000, 'stock'=>150, 'min_stock'=>15,  'manufacturer'=>'داروسازی پارس'],

            // ─── چشم و گوش ──────────────────────────────────
            ['category_slug'=>'ent', 'name'=>'قطره چشم کلرامفنیکل',         'generic_name'=>'Chloramphenicol Eye Drop', 'barcode'=>'ENT001', 'unit'=>'عدد',   'purchase_price'=>8000,  'sale_price'=>13000, 'stock'=>200, 'min_stock'=>20,  'manufacturer'=>'لابراتوار البرز'],
            ['category_slug'=>'ent', 'name'=>'قطره گوش جنتامایسین',         'generic_name'=>'Gentamicin Ear Drop',     'barcode'=>'ENT002', 'unit'=>'عدد',   'purchase_price'=>10000, 'sale_price'=>16000, 'stock'=>160, 'min_stock'=>15,  'manufacturer'=>'داروسازی ایران'],
        ];

        foreach ($medicines as $data) {
            $catSlug = $data['category_slug'];
            unset($data['category_slug']);

            $data['category_id']           = $cats[$catSlug] ?? $cats->first();
            $data['is_active']              = true;
            $data['requires_prescription']  = $data['requires_prescription'] ?? false;
            $data['expiry_date']            = $data['expiry_date'] ?? null;

            $med = Medicine::firstOrCreate(
                ['barcode' => $data['barcode']],
                $data
            );

            // ثبت ورود اولیه انبار
            if ($med->wasRecentlyCreated && $med->stock > 0) {
                StockMovement::create([
                    'medicine_id'  => $med->id,
                    'user_id'      => $adminId,
                    'type'         => 'in',
                    'quantity'     => $med->stock,
                    'stock_before' => 0,
                    'stock_after'  => $med->stock,
                    'note'         => 'موجودی اولیه — Seeder',
                ]);
            }
        }

        $this->command->info('✅ ' . count($medicines) . ' دارو وارد شد.');
    }
}


