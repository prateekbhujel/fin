@php($title = 'Create Category')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Create Category" description="Create a new income or expense category." />

    <form method="POST" action="{{ route('categories.store') }}" class="card p-6">
        @csrf
        @include('modules.categories.partials.form', ['submitLabel' => 'Create Category'])
    </form>
@endsection
