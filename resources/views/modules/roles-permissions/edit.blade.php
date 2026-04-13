@php($title = 'Edit Role Permissions')
@extends('layouts.app')

@section('content')
    <x-admin.page-header :title="'Edit '.ucfirst($role->name).' Permissions'" description="Choose which modules and actions this role can access." />

    <form method="POST" action="{{ route('roles-permissions.update', $role) }}" class="card p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @foreach ($permissionGroups as $group => $permissions)
                <div class="rounded-2xl border border-gray-200 p-5 dark:border-gray-800">
                    <div class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-gray-400">{{ str_replace('_', ' ', $group) }}</div>
                    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-200">
                                <input
                                    type="checkbox"
                                    class="form-checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    @checked(in_array($permission->name, old('permissions', $role->permissions->pluck('name')->all()), true))
                                >
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('roles-permissions.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary" type="submit">Save Permissions</button>
        </div>
    </form>
@endsection
