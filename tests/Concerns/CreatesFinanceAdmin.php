<?php

namespace Tests\Concerns;

use App\Models\User;
use Database\Seeders\PermissionSeeder;

trait CreatesFinanceAdmin
{
    protected function createFinanceAdmin(): User
    {
        $this->seed(PermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }
}
