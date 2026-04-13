@php($title = 'Reports')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Reports" description="Monthly, yearly, and category-based summaries with AD and BS range filters." />

    <form class="card mb-6 grid gap-4 p-5 lg:grid-cols-4">
        <div>
            <x-input-label for="type" value="Type" />
            <select id="type" name="type" class="form-select mt-1">
                <option value="">All types</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['type'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="category_id" value="Category" />
            <select id="category_id" name="category_id" class="form-select mt-1">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="date_from" value="Date From (AD)" />
            <input id="date_from" name="date_from" data-flatpickr value="{{ $filters['date_from'] ?? '' }}" class="form-input mt-1">
        </div>
        <div>
            <x-input-label for="date_to" value="Date To (AD)" />
            <input id="date_to" name="date_to" data-flatpickr value="{{ $filters['date_to'] ?? '' }}" class="form-input mt-1">
        </div>
        <div>
            <x-input-label for="from_bs" value="Date From (BS)" />
            <input id="from_bs" name="from_bs" value="{{ $filters['from_bs'] ?? '' }}" class="form-input mt-1" placeholder="2082-01-01">
        </div>
        <div>
            <x-input-label for="to_bs" value="Date To (BS)" />
            <input id="to_bs" name="to_bs" value="{{ $filters['to_bs'] ?? '' }}" class="form-input mt-1" placeholder="2082-01-30">
        </div>
        <div class="flex items-end gap-3">
            <button class="btn-primary" type="submit">Apply</button>
            <a href="{{ route('reports.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat-card label="Income" :value="npr($overview['income'])" tone="success" />
        <x-admin.stat-card label="Expense" :value="npr($overview['expense'])" tone="warning" />
        <x-admin.stat-card label="Balance" :value="npr($overview['balance'])" tone="brand" />
        <x-admin.stat-card label="Entries" :value="number_format($overview['transactions_count'])" tone="brand" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="card p-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Report</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="table-th">Month</th>
                            <th class="table-th text-right">Income</th>
                            <th class="table-th text-right">Expense</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($monthlySummary as $row)
                            <tr>
                                <td class="table-td">{{ $row->period }}</td>
                                <td class="table-td text-right">{{ npr($row->income_total) }}</td>
                                <td class="table-td text-right">{{ npr($row->expense_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Yearly Report</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="table-th">Year</th>
                            <th class="table-th text-right">Income</th>
                            <th class="table-th text-right">Expense</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($yearlySummary as $row)
                            <tr>
                                <td class="table-td">{{ $row->period }}</td>
                                <td class="table-td text-right">{{ npr($row->income_total) }}</td>
                                <td class="table-td text-right">{{ npr($row->expense_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6 card p-5">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Category Summary</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="table-th">Category</th>
                        <th class="table-th">Type</th>
                        <th class="table-th text-right">Entries</th>
                        <th class="table-th text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($categorySummary as $row)
                        <tr>
                            <td class="table-td">{{ $row->category_name ?? 'Uncategorized' }}</td>
                            <td class="table-td">{{ ucfirst($row->type) }}</td>
                            <td class="table-td text-right">{{ number_format($row->total_entries) }}</td>
                            <td class="table-td text-right">{{ npr($row->total_amount) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
