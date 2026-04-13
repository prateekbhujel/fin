@php($title = 'Transactions')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Transactions" description="Track income and expense entries with AD and BS date filtering.">
        @can('transactions.create')
            <a href="{{ route('transactions.create') }}" class="btn-primary">Add Transaction</a>
        @endcan
    </x-admin.page-header>

    <form class="card mb-6 grid gap-4 p-5 lg:grid-cols-4">
        <div>
            <x-input-label for="search" value="Search" />
            <input id="search" name="search" class="form-input mt-1" value="{{ request('search') }}" placeholder="Search transaction">
        </div>
        <div>
            <x-input-label for="type" value="Type" />
            <select id="type" name="type" class="form-select mt-1">
                <option value="">All types</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="category_id" value="Category" />
            <select id="category_id" name="category_id" class="form-select mt-1">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="date_from" value="Date From (AD)" />
            <input id="date_from" name="date_from" data-flatpickr value="{{ request('date_from') }}" class="form-input mt-1">
        </div>
        <div>
            <x-input-label for="date_to" value="Date To (AD)" />
            <input id="date_to" name="date_to" data-flatpickr value="{{ request('date_to') }}" class="form-input mt-1">
        </div>
        <div>
            <x-input-label for="from_bs" value="Date From (BS)" />
            <input id="from_bs" name="from_bs" value="{{ request('from_bs') }}" class="form-input mt-1" placeholder="2082-01-01">
        </div>
        <div>
            <x-input-label for="to_bs" value="Date To (BS)" />
            <input id="to_bs" name="to_bs" value="{{ request('to_bs') }}" class="form-input mt-1" placeholder="2082-01-30">
        </div>
        <div class="flex items-end gap-3">
            <button class="btn-primary" type="submit">Filter</button>
            <a href="{{ route('transactions.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/70">
                    <tr>
                        <th class="table-th">Title</th>
                        <th class="table-th">Category</th>
                        <th class="table-th">Date</th>
                        <th class="table-th">Type</th>
                        <th class="table-th text-right">Amount</th>
                        <th class="table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="table-td">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $transaction->title }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->reference_no ?: 'No reference' }}</div>
                            </td>
                            <td class="table-td">{{ $transaction->category?->name ?? 'Uncategorized' }}</td>
                            <td class="table-td">
                                {{ optional($transaction->transaction_date)->format('Y-m-d') }}
                                @if ($transaction->transaction_bs)
                                    <div class="text-xs text-gray-400">{{ $transaction->transaction_bs }}</div>
                                @endif
                            </td>
                            <td class="table-td">
                                <span class="{{ $transaction->type === 'income' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="table-td text-right font-semibold">{{ npr($transaction->amount) }}</td>
                            <td class="table-td text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary">View</a>
                                    @can('transactions.update')
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="btn-secondary">Edit</a>
                                    @endcan
                                    @can('transactions.delete')
                                        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Delete this transaction?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-danger" type="submit">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-td text-center text-gray-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
@endsection
