@php($title = 'Categories')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Categories" description="Organize income and expense entries into practical account heads.">
        @can('categories.create')
            <a href="{{ route('categories.create') }}" class="btn-primary">Add Category</a>
        @endcan
    </x-admin.page-header>

    <form class="card mb-6 grid gap-4 p-5 md:grid-cols-[1fr_220px_auto]">
        <div>
            <x-input-label for="search" value="Search" />
            <input id="search" name="search" class="form-input mt-1" value="{{ request('search') }}" placeholder="Search category name">
        </div>
        <div>
            <x-input-label for="type" value="Type" />
            <select id="type" name="type" class="form-select mt-1">
                <option value="">All types</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-3">
            <button class="btn-primary" type="submit">Filter</button>
            <a href="{{ route('categories.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/70">
                    <tr>
                        <th class="table-th">Category</th>
                        <th class="table-th">Type</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="table-td">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</div>
                                <div class="text-xs text-gray-500">{{ $category->description ?: 'No description' }}</div>
                            </td>
                            <td class="table-td">
                                <span class="{{ $category->type === 'income' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($category->type) }}
                                </span>
                            </td>
                            <td class="table-td">
                                <span class="{{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="table-td text-right">
                                <div class="flex justify-end gap-2">
                                    @can('categories.update')
                                        <a href="{{ route('categories.edit', $category) }}" class="btn-secondary">Edit</a>
                                    @endcan
                                    @can('categories.delete')
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
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
                            <td colspan="4" class="table-td text-center text-gray-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
