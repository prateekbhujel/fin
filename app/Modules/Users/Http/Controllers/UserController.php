<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Users\DTOs\UserData;
use App\Modules\Users\Http\Requests\StoreUserRequest;
use App\Modules\Users\Http\Requests\UpdateUserRequest;
use App\Modules\Users\Services\UserService;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        protected UserService $users,
    ) {
    }

    public function index()
    {
        return view('modules.users.index', [
            'users' => $this->users->paginate(request()->only(['search', 'role'])),
            'roles' => $this->users->roles(),
        ]);
    }

    public function create()
    {
        return view('modules.users.create', [
            'roles' => $this->users->roles(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->users->store(UserData::fromArray(
            data: $request->validated(),
            isActive: $request->boolean('is_active', true),
        ));

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
            'roles' => $this->users->roles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->users->update($user, UserData::fromArray(
            data: $request->validated(),
            isActive: $request->boolean('is_active'),
        ));

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        try {
            $this->users->delete($user, auth()->user());
        } catch (ValidationException $exception) {
            return back()->with('error', $exception->errors()['user'][0] ?? 'Unable to delete this user.');
        }

        return redirect()->route('users.index')->with('status', 'User removed successfully.');
    }
}
