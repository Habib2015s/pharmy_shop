<?php

// ============================================================
// فایل: database/seeders/PharmacySeeder.php
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Pharmacy, User};

class PharmacySeeder extends Seeder
{
    public function run(): void
    {
        $pharmacyUsers = User::role('pharmacy')->get();

        $pharmacies = [
            [
                'name'           => 'داروخانه شفا',
                'owner_name'     => 'دکتر محمد شریفی',
                'license_number' => 'ISF-2001',
                'phone'          => '031-32201100',
                'mobile'         => '09131001001',
                'email'          => 'shafa@pharma.ir',
                'province'       => 'اصفهان',
                'city'           => 'اصفهان',
                'address'        => 'خیابان نظر، پلاک ۴۵',
                'credit_limit'   => 80_000_000,
                'current_balance'=> 12_500_000,
                'is_active'      => true,
            ],
            [
                'name'           => 'داروخانه مهر',
                'owner_name'     => 'دکتر فاطمه رضایی',
                'license_number' => 'SHR-1002',
                'phone'          => '038-32301200',
                'mobile'         => '09131002002',
                'email'          => 'mehr@pharma.ir',
                'province'       => 'چهارمحال و بختیاری',
                'city'           => 'شهرکرد',
                'address'        => 'بلوار هشت بهشت، پلاک ۱۲',
                'credit_limit'   => 50_000_000,
                'current_balance'=> 8_700_000,
                'is_active'      => true,
            ],
            [
                'name'           => 'داروخانه سینا',
                'owner_name'     => 'دکتر علی احمدی',
                'license_number' => 'THR-3003',
                'phone'          => '021-88001100',
                'mobile'         => '09121003003',
                'email'          => 'sina@pharma.ir',
                'province'       => 'تهران',
                'city'           => 'تهران',
                'address'        => 'خیابان ولیعصر، تقاطع توانیر',
                'credit_limit'   => 120_000_000,
                'current_balance'=> 0,
                'is_active'      => true,
            ],
            [
                'name'           => 'داروخانه ابن سینا',
                'owner_name'     => 'دکتر مریم حسینی',
                'license_number' => 'ISF-4004',
                'phone'          => '031-44001200',
                'mobile'         => '09131004004',
                'email'          => 'ibnsina@pharma.ir',
                'province'       => 'اصفهان',
                'city'           => 'کاشان',
                'address'        => 'خیابان پانزده خرداد، پلاک ۷۸',
                'credit_limit'   => 40_000_000,
                'current_balance'=> 5_200_000,
                'is_active'      => true,
            ],
            [
                'name'           => 'داروخانه رازی',
                'owner_name'     => 'دکتر حسن موسوی',
                'license_number' => 'MSD-5005',
                'phone'          => '051-38001300',
                'mobile'         => '09151005005',
                'email'          => 'razi@pharma.ir',
                'province'       => 'خراسان رضوی',
                'city'           => 'مشهد',
                'address'        => 'بلوار وکیل‌آباد، پلاک ۲۳',
                'credit_limit'   => 60_000_000,
                'current_balance'=> 15_800_000,
                'is_active'      => true,
            ],
            [
                'name'           => 'داروخانه امید',
                'owner_name'     => 'دکتر زهرا کریمی',
                'license_number' => 'SHZ-6006',
                'phone'          => '071-32501400',
                'mobile'         => '09171006006',
                'email'          => 'omid@pharma.ir',
                'province'       => 'فارس',
                'city'           => 'شیراز',
                'address'        => 'خیابان زند، پلاک ۵۶',
                'credit_limit'   => 35_000_000,
                'current_balance'=> 0,
                'is_active'      => false, // غیرفعال
            ],
        ];

        foreach ($pharmacies as $i => $data) {
            $ph = Pharmacy::firstOrCreate(
                ['license_number' => $data['license_number']],
                $data
            );

            // اتصال به کاربر داروخانه
            if ($pharmacyUsers->has($i)) {
                $ph->update(['user_id' => $pharmacyUsers[$i]->id]);
            }
        }

        $this->command->info('✅ ' . count($pharmacies) . ' داروخانه وارد شد.');
    }
}

