@php($title = 'Users')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Users" description="Manage admin and staff accounts, role assignment, and access status.">
        @can('users.create')
            <a href="{{ route('users.create') }}" class="btn-primary">Add User</a>
        @endcan
    </x-admin.page-header>

    <form class="card mb-6 grid gap-4 p-5 md:grid-cols-[1fr_220px_auto]">
        <div>
            <x-input-label for="search" value="Search" />
            <input id="search" name="search" value="{{ request('search') }}" class="form-input mt-1" placeholder="Search by name, email, or phone">
        </div>
        <div>
            <x-input-label for="role" value="Role" />
            <select id="role" name="role" class="form-select mt-1">
                <option value="">All roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-3">
            <button class="btn-primary" type="submit">Filter</button>
            <a href="{{ route('users.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/70">
                    <tr>
                        <th class="table-th">User</th>
                        <th class="table-th">Role</th>
                        <th class="table-th">Phone</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr>
                            <td class="table-td">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="table-td">{{ ucfirst($user->roles->first()?->name ?? 'N/A') }}</td>
                            <td class="table-td">{{ $user->phone ?: 'N/A' }}</td>
                            <td class="table-td">
                                <span class="{{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="table-td text-right">
                                <div class="flex justify-end gap-2">
                                    @can('users.update')
                                        <a href="{{ route('users.edit', $user) }}" class="btn-secondary">Edit</a>
                                    @endcan
                                    @can('users.delete')
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-danger" type="submit">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-td text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
