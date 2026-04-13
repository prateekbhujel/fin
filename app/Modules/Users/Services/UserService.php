<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use App\Modules\Users\DTOs\UserData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserService
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query()->with('roles')->latest();

        if ($search = $filters['search'] ?? null) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $filters['role'] ?? null) {
            $query->role($role);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function roles(): Collection
    {
        return Role::query()->pluck('name');
    }

    public function store(UserData $data): User
    {
        $user = User::create($data->modelAttributes());
        $user->syncRoles([$data->role]);

        return $user->load('roles');
    }

    public function update(User $user, UserData $data): User
    {
        $user->update($data->modelAttributes(includePassword: filled($data->password)));
        $user->syncRoles([$data->role]);

        return $user->load('roles');
    }

    public function delete(User $user, ?User $actingUser = null): void
    {
        if ($actingUser && $user->is($actingUser)) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        $user->delete();
    }
}
