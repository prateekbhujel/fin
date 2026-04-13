@php
    $title = 'Dashboard';
    $dashboardExperienceData = [
        'summary' => [
            'income' => (float) $summary['income'],
            'expense' => (float) $summary['expense'],
            'balance' => (float) $summary['balance'],
            'transactionsCount' => (int) $summary['transactions_count'],
        ],
        'monthlyTrend' => [
            'labels' => $monthlyTrend['labels'],
            'income' => array_map(fn ($value) => (float) $value, $monthlyTrend['income']),
            'expense' => array_map(fn ($value) => (float) $value, $monthlyTrend['expense']),
        ],
        'announcements' => $announcements->map(fn ($announcement) => [
            'id' => $announcement->id,
            'title' => $announcement->title,
            'content' => $announcement->content,
            'publishedAt' => optional($announcement->published_at)?->toISOString(),
        ])->values()->all(),
        'recentTransactions' => $recentTransactions->map(fn ($transaction) => [
            'id' => $transaction->id,
            'title' => $transaction->title,
            'type' => $transaction->type,
            'amount' => (float) $transaction->amount,
            'categoryName' => $transaction->category?->name,
            'transactionDate' => optional($transaction->transaction_date)?->toDateString(),
            'transactionBs' => $transaction->transaction_bs,
            'showUrl' => route('transactions.show', $transaction),
        ])->values()->all(),
    ];
@endphp
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Dashboard" description="See what needs attention before the day gets busy." />

    <div id="dashboard-experience-root">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-admin.stat-card label="Total Income" :value="npr($summary['income'])" tone="success" />
            <x-admin.stat-card label="Total Expense" :value="npr($summary['expense'])" tone="warning" />
            <x-admin.stat-card label="Current Balance" :value="npr($summary['balance'])" tone="brand" />
            <x-admin.stat-card label="Entries" :value="number_format($summary['transactions_count'])" tone="brand" />
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            <div class="card p-5">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Trend</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Income and expense movement over the last six months.</p>
                    </div>
                </div>
                <div
                    id="dashboardTrendChart"
                    data-labels='@json($monthlyTrend['labels'])'
                    data-income='@json($monthlyTrend['income'])'
                    data-expense='@json($monthlyTrend['expense'])'
                ></div>
            </div>

            <div class="card p-5">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Announcements</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($announcements as $announcement)
                        <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-800">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $announcement->title }}</div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $announcement->content }}</p>
                        </div>
                    @empty
                        <x-admin.empty-state title="No announcements" description="Published notices will appear here." />
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-6 card">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/60">
                        <tr>
                            <th class="table-th">Title</th>
                            <th class="table-th">Type</th>
                            <th class="table-th">Category</th>
                            <th class="table-th">Date</th>
                            <th class="table-th text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td class="table-td">{{ $transaction->title }}</td>
                                <td class="table-td">
                                    <span class="{{ $transaction->type === 'income' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="table-td">{{ $transaction->category?->name ?? 'Uncategorized' }}</td>
                                <td class="table-td">
                                    {{ optional($transaction->transaction_date)->format('Y-m-d') }}
                                    @if ($transaction->transaction_bs)
                                        <div class="text-xs text-gray-400">{{ $transaction->transaction_bs }}</div>
                                    @endif
                                </td>
                                <td class="table-td text-right font-semibold">{{ npr($transaction->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="table-td text-center text-gray-500">No transactions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script id="dashboard-experience-data" type="application/json">@json($dashboardExperienceData)</script>
@endsection
