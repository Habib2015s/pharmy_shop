<?php


// ============================================================
// فایل: database/seeders/PermissionSeeder.php
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // پاک کردن cache مجوزها
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage medicines',
            'view orders',
            'manage orders',
            'manage pharmacies',
            'view reports',
            'manage users',
            'manage categories',
            'manage invoices',
            'manage stock',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ─── نقش‌ها ─────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions); // همه مجوزها

        $distributor = Role::firstOrCreate(['name' => 'distributor']);
        $distributor->syncPermissions([
            'view orders', 'manage orders', 'manage stock', 'view reports'
        ]);

        $pharmacy = Role::firstOrCreate(['name' => 'pharmacy']);
        $pharmacy->syncPermissions(['view orders']);

        $this->command->info('✅ مجوزها و نقش‌ها ساخته شدند.');
    }
}

