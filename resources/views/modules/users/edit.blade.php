@php($title = 'Edit User')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Edit User" description="Update account details and role assignment." />

    <form method="POST" action="{{ route('users.update', $user) }}" class="card p-6">
        @csrf
        @method('PUT')
        @include('modules.users.partials.form', ['submitLabel' => 'Save Changes'])
    </form>
@endsection
