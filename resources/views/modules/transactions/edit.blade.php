@php($title = 'Edit Transaction')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Edit Transaction" description="Update entry details, attachments, or dates." />

    <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data" class="card p-6">
        @csrf
        @method('PUT')
        @include('modules.transactions.partials.form', ['submitLabel' => 'Save Changes'])
    </form>
@endsection
