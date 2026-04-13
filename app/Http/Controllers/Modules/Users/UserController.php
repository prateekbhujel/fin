<?php

namespace App\Http\Controllers\Modules\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Users\StoreUserRequest;
use App\Http\Requests\Modules\Users\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query()->with('roles')->latest();

        if ($search = request('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = request('role')) {
            $query->role($role);
        }

        return view('modules.users.index', [
            'users' => $query->paginate(10)->withQueryString(),
            'roles' => Role::query()->pluck('name'),
        ]);
    }

    public function create()
    {
        return view('modules.users.create', [
            'roles' => Role::query()->pluck('name'),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'password' => $validated['password'],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    public function show(User $user)
    {
        return redirect()->route('users.edit', $user);
    }

    public function edit(User $user)
    {
        return view('modules.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::query()->pluck('name'),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
            ...filled($validated['password'] ?? null) ? ['password' => $validated['password']] : [],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'User removed successfully.');
    }
}
