@php($title = 'Edit Category')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Edit Category" description="Update the selected category and its status." />

    <form method="POST" action="{{ route('categories.update', $category) }}" class="card p-6">
        @csrf
        @method('PUT')
        @include('modules.categories.partials.form', ['submitLabel' => 'Save Changes'])
    </form>
@endsection
