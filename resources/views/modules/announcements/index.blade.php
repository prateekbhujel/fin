@php($title = 'Announcements')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Announcements" description="Publish notices that appear on the dashboard for staff and admins.">
        @can('announcements.create')
            <a href="{{ route('announcements.create') }}" class="btn-primary">New Announcement</a>
        @endcan
    </x-admin.page-header>

    <div class="space-y-4">
        @forelse ($announcements as $announcement)
            <div class="card p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $announcement->title }}</h2>
                            <span class="{{ $announcement->is_published ? 'badge-success' : 'badge-danger' }}">
                                {{ $announcement->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $announcement->content }}</p>
                    </div>
                    <div class="flex gap-2">
                        @can('announcements.update')
                            <a href="{{ route('announcements.edit', $announcement) }}" class="btn-secondary">Edit</a>
                        @endcan
                        @can('announcements.delete')
                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger" type="submit">Delete</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <x-admin.empty-state title="No announcements" description="Create a notice to show important updates on the dashboard." />
        @endforelse
    </div>

    <div class="mt-4">{{ $announcements->links() }}</div>
@endsection
