@php($title = 'Create User')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Create User" description="Add a new admin or staff account." />

    <form method="POST" action="{{ route('users.store') }}" class="card p-6">
        @csrf
        @include('modules.users.partials.form', ['submitLabel' => 'Create User'])
    </form>
@endsection
