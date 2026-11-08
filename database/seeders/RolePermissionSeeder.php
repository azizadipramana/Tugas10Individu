<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create permissions
        $permissions = [
            'view-dashboard',
            'view-academic-data',
            'create-academic-data',
            'edit-academic-data',
            'delete-academic-data', // Exclusive to Admin
            'manage-users',         // Exclusive to Admin
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 2. Create roles
        // Peran Admin (Akses Penuh)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // Peran Staf (Akses Terbatas: tidak memiliki izin delete-academic-data dan manage-users)
        $stafRole = Role::firstOrCreate(['name' => 'Staf', 'guard_name' => 'web']);
        $stafRole->syncPermissions([
            'view-dashboard',
            'view-academic-data',
            'create-academic-data',
            'edit-academic-data',
        ]);

        // 3. Create default test accounts
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@akademik.its.ac.id'],
            [
                'name' => 'Administrator Akademik',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->syncRoles(['Admin']);

        $stafUser = User::firstOrCreate(
            ['email' => 'staf@akademik.its.ac.id'],
            [
                'name' => 'Staf Administrasi Akademik',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $stafUser->syncRoles(['Staf']);

        // Akun Staf Kedua untuk variasi pengujian
        $stafUser2 = User::firstOrCreate(
            ['email' => 'budi.staf@akademik.its.ac.id'],
            [
                'name' => 'Budi Santoso (Staf)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $stafUser2->syncRoles(['Staf']);
    }
}
