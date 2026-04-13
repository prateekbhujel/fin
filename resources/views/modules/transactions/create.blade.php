@php($title = 'Create Transaction')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Create Transaction" description="Record a new income or expense entry." />

    <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data" class="card p-6">
        @csrf
        @include('modules.transactions.partials.form', ['submitLabel' => 'Save Transaction'])
    </form>
@endsection
