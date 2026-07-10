<?php


// ============================================================
// فایل: database/seeders/UserSeeder.php
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'              => 'مدیر سیستم',
                'email'             => 'admin@pharma.ir',
                'password'          => Hash::make('admin123456'),
                'email_verified_at' => now(),
                'role'              => 'admin',
            ],
            [
                'name'              => 'علی رضایی',
                'email'             => 'dist@pharma.ir',
                'password'          => Hash::make('dist123456'),
                'email_verified_at' => now(),
                'role'              => 'distributor',
            ],
            [
                'name'              => 'فاطمه احمدی',
                'email'             => 'dist2@pharma.ir',
                'password'          => Hash::make('dist123456'),
                'email_verified_at' => now(),
                'role'              => 'distributor',
            ],
            [
                'name'              => 'داروخانه شفا',
                'email'             => 'pharmacy@pharma.ir',
                'password'          => Hash::make('pharmacy123'),
                'email_verified_at' => now(),
                'role'              => 'pharmacy',
            ],
            [
                'name'              => 'داروخانه مهر',
                'email'             => 'pharmacy2@pharma.ir',
                'password'          => Hash::make('pharmacy123'),
                'email_verified_at' => now(),
                'role'              => 'pharmacy',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(['email' => $data['email']], $data);
            $user->syncRoles([$role]);
        }

        $this->command->info('✅ کاربران ساخته شدند.');
    }
}

