@php($title = 'Documents')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Documents" description="Upload supporting files and keep them attached to finance records." />

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="card p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Documents</h2>

            <div class="mt-5">
                <x-input-label for="transaction_id" value="Attach to Transaction" />
                <select id="transaction_id" name="transaction_id" class="form-select mt-1">
                    <option value="">Standalone document</option>
                    @foreach ($transactions as $transaction)
                        <option value="{{ $transaction->id }}">{{ $transaction->title }} - {{ npr($transaction->amount) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-5">
                <x-input-label for="description" value="Description" />
                <input id="description" name="description" class="form-input mt-1" placeholder="Invoice, receipt, signed letter...">
            </div>

            <div class="mt-5">
                <x-input-label for="files" value="Files" />
                <input id="files" name="files[]" type="file" multiple class="form-input mt-1" required>
                <x-input-error :messages="$errors->get('files')" class="mt-2" />
                <x-input-error :messages="$errors->get('files.*')" class="mt-2" />
            </div>

            <div class="mt-6">
                <button class="btn-primary" type="submit">Upload Documents</button>
            </div>
        </form>

        <div class="card p-6">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stored Documents</h2>
                <form class="grid gap-3 md:grid-cols-[minmax(0,1fr)_220px_auto]">
                    <input name="search" value="{{ request('search') }}" class="form-input" placeholder="Search filename">
                    <select name="transaction_id" class="form-select">
                        <option value="">All transactions</option>
                        @foreach ($transactions as $transaction)
                            <option value="{{ $transaction->id }}" @selected((string) request('transaction_id') === (string) $transaction->id)>
                                {{ $transaction->title }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn-secondary" type="submit">Search</button>
                </form>
            </div>

            <div class="space-y-3">
                @forelse ($documents as $document)
                    <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-800">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $document->original_name }}</div>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $document->transaction?->title ?? 'Standalone' }} · {{ number_format($document->size / 1024, 1) }} KB
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('documents.download', $document) }}" class="btn-secondary">Download</a>
                                @can('documents.delete')
                                    <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger" type="submit">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <x-admin.empty-state title="No documents yet" description="Upload receipts, invoices, and related files here." />
                @endforelse
            </div>

            <div class="mt-4">{{ $documents->links() }}</div>
        </div>
    </div>
@endsection
