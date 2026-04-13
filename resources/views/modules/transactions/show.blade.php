@php($title = 'Transaction Details')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Transaction Details" description="Review transaction information and attached documents.">
        <a href="{{ route('transactions.index') }}" class="btn-secondary">Back</a>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
        <div class="card p-6">
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Title</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">{{ $transaction->title }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Amount</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">{{ npr($transaction->amount) }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Type</div>
                    <div class="mt-2">{{ ucfirst($transaction->type) }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Category</div>
                    <div class="mt-2">{{ $transaction->category?->name ?? 'Uncategorized' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Transaction Date</div>
                    <div class="mt-2">{{ optional($transaction->transaction_date)->format('Y-m-d') }}</div>
                    @if ($transaction->transaction_bs)
                        <div class="text-sm text-gray-500">BS: {{ $transaction->transaction_bs }}</div>
                    @endif
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Payment Method</div>
                    <div class="mt-2">{{ config('finance.payment_methods.'.$transaction->payment_method, $transaction->payment_method) }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Reference No</div>
                    <div class="mt-2">{{ $transaction->reference_no ?: 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Recorded By</div>
                    <div class="mt-2">{{ $transaction->user?->name }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400">Notes</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $transaction->notes ?: 'No notes provided.' }}</div>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Documents</h2>
            <div class="mt-4 space-y-3">
                @forelse ($transaction->documents as $document)
                    <a href="{{ route('documents.download', $document) }}" class="flex items-center justify-between rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-200">
                        <span>{{ $document->original_name }}</span>
                        <span class="text-gray-400">Download</span>
                    </a>
                @empty
                    <x-admin.empty-state title="No documents" description="Upload files from the transaction edit screen." />
                @endforelse
            </div>
        </div>
    </div>
@endsection
