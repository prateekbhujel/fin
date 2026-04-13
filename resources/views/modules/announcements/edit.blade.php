@php($title = 'Edit Announcement')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Edit Announcement" description="Update content, visibility, and publish timing." />

    <form method="POST" action="{{ route('announcements.update', $announcement) }}" class="card p-6">
        @csrf
        @method('PUT')
        @include('modules.announcements.partials.form', ['submitLabel' => 'Save Changes'])
    </form>
@endsection
