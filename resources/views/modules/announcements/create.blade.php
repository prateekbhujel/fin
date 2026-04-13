@php($title = 'Create Announcement')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Create Announcement" description="Publish a new dashboard notice." />

    <form method="POST" action="{{ route('announcements.store') }}" class="card p-6">
        @csrf
        @include('modules.announcements.partials.form', ['submitLabel' => 'Create Announcement'])
    </form>
@endsection
