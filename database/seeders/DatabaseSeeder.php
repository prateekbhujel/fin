<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        foreach (config('finance.setting_defaults', []) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => explode('.', $key)[0] ?? 'general',
                    'value' => $value,
                    'type' => is_bool($value) ? 'boolean' : 'string',
                ]
            );
        }

        foreach ([
            ['name' => 'Sales', 'type' => 'income'],
            ['name' => 'Service Charge', 'type' => 'income'],
            ['name' => 'Office Rent', 'type' => 'expense'],
            ['name' => 'Utilities', 'type' => 'expense'],
        ] as $category) {
            Category::updateOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                ['description' => $category['name'].' category', 'is_active' => true]
            );
        }

        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@finance.test'))->first();

        Announcement::updateOrCreate([
            'title' => 'Welcome to the finance dashboard',
        ], [
            'user_id' => $admin?->id,
            'content' => 'Review daily income and expense entries, update settings, and keep your reports current.',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
