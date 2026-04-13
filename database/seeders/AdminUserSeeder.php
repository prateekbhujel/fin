<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@fin.test')],
            [
                'name' => env('ADMIN_NAME', 'System Admin'),
                'phone' => env('ADMIN_PHONE', '9800000000'),
                'designation' => 'Administrator',
                'is_active' => true,
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        $admin->syncRoles(['admin']);

        $staff = User::updateOrCreate(
            ['email' => env('STAFF_EMAIL', 'staff@fin.test')],
            [
                'name' => env('STAFF_NAME', 'Finance Staff'),
                'phone' => env('STAFF_PHONE', '9811111111'),
                'designation' => 'Account Staff',
                'is_active' => true,
                'password' => Hash::make(env('STAFF_PASSWORD', 'password')),
            ]
        );

        $staff->syncRoles(['staff']);
    }
}
