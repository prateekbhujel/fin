@php($title = 'Roles & Permissions')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Roles & Permissions" description="Review how access is distributed across admin and staff roles." />

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach ($roles as $role)
            <div class="card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($role->name) }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $role->permissions->count() }} permissions assigned</p>
                    </div>
                    @can('roles_permissions.update')
                        <a href="{{ route('roles-permissions.edit', $role) }}" class="btn-secondary">Edit</a>
                    @endcan
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($role->permissions->take(10) as $permission)
                        <span class="badge bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200">{{ $permission->name }}</span>
                    @endforeach
                    @if ($role->permissions->count() > 10)
                        <span class="badge bg-brand-50 text-brand-600">+{{ $role->permissions->count() - 10 }} more</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
