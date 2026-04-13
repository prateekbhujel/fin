import type { ApexOptions } from 'apexcharts';
import { ConfigProvider, Empty, Segmented, Tag, Tooltip } from 'antd';
import { useState } from 'react';
import ReactApexChart from 'react-apexcharts';
import { formatAdDate, formatCurrency } from './utils';

type AnnouncementItem = {
    id: number;
    title: string;
    content: string;
    publishedAt: string | null;
};

type RecentTransactionItem = {
    id: number;
    title: string;
    type: 'income' | 'expense';
    amount: number;
    categoryName: string | null;
    transactionDate: string | null;
    transactionBs: string | null;
    showUrl: string;
};

type DashboardExperienceProps = {
    summary: {
        income: number;
        expense: number;
        balance: number;
        transactionsCount: number;
    };
    monthlyTrend: {
        labels: string[];
        income: number[];
        expense: number[];
    };
    announcements: AnnouncementItem[];
    recentTransactions: RecentTransactionItem[];
};

type TrendMode = 'both' | 'income' | 'expense';

export function DashboardExperience({
    summary,
    monthlyTrend,
    announcements,
    recentTransactions,
}: DashboardExperienceProps) {
    const [trendMode, setTrendMode] = useState<TrendMode>('both');

    const series =
        trendMode === 'income'
            ? [{ name: 'Income', data: monthlyTrend.income }]
            : trendMode === 'expense'
              ? [{ name: 'Expense', data: monthlyTrend.expense }]
              : [
                    { name: 'Income', data: monthlyTrend.income },
                    { name: 'Expense', data: monthlyTrend.expense },
                ];

    const chartOptions: ApexOptions = {
        chart: {
            type: 'area',
            height: 340,
            toolbar: { show: false },
            fontFamily: 'Outfit, sans-serif',
        },
        colors: trendMode === 'income' ? ['#0ea5e9'] : trendMode === 'expense' ? ['#f97316'] : ['#405cf5', '#f97316'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: monthlyTrend.labels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#64748b' } },
        },
        yaxis: {
            labels: {
                formatter: (value) => `NPR ${Number(value).toLocaleString()}`,
                style: { colors: '#64748b' },
            },
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            labels: { colors: '#475569' },
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 5,
        },
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.32,
                opacityTo: 0.04,
            },
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: (value) => `NPR ${Number(value).toLocaleString()}`,
            },
        },
    };

    const statCards = [
        {
            label: 'Income',
            value: summary.income,
            accent: 'from-sky-500/20 to-sky-50',
            tone: 'text-sky-600',
        },
        {
            label: 'Expense',
            value: summary.expense,
            accent: 'from-orange-500/20 to-orange-50',
            tone: 'text-orange-600',
        },
        {
            label: 'Balance',
            value: summary.balance,
            accent: 'from-brand-500/20 to-brand-50',
            tone: 'text-brand-600',
        },
        {
            label: 'Entries',
            value: summary.transactionsCount,
            accent: 'from-emerald-500/20 to-emerald-50',
            tone: 'text-emerald-600',
            formatter: (value: number) => value.toLocaleString(),
        },
    ];

    return (
        <ConfigProvider
            theme={{
                token: {
                    colorPrimary: '#405cf5',
                    borderRadius: 18,
                    fontFamily: 'Outfit, sans-serif',
                },
            }}
        >
            <div className="space-y-6">
                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    {statCards.map((card) => (
                        <div key={card.label} className="card overflow-hidden p-5">
                            <div className={`inline-flex rounded-full bg-gradient-to-br px-3 py-1.5 text-xs font-semibold ${card.accent} ${card.tone}`}>
                                {card.label}
                            </div>
                            <div className="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">
                                {card.formatter ? card.formatter(card.value) : `NPR ${formatCurrency(card.value)}`}
                            </div>
                            <div className="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {card.label === 'Balance'
                                    ? 'What is still available after current expenses.'
                                    : card.label === 'Entries'
                                      ? 'Transactions recorded across all categories.'
                                      : `Current ${card.label.toLowerCase()} total.`}
                            </div>
                        </div>
                    ))}
                </div>

                <div className="grid gap-6 xl:grid-cols-[1.65fr_0.95fr]">
                    <section className="card p-5">
                        <div className="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div className="flex items-center gap-3">
                                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Monthly movement</h2>
                                    <Tag color="blue" className="!mr-0 !rounded-full !border-0 !px-3 !py-1">
                                        TailAdmin style
                                    </Tag>
                                </div>
                                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    A quick read on how money moved over the last six months.
                                </p>
                            </div>

                            <Segmented<TrendMode>
                                value={trendMode}
                                onChange={(value) => setTrendMode(value)}
                                options={[
                                    { label: 'Both', value: 'both' },
                                    { label: 'Income', value: 'income' },
                                    { label: 'Expense', value: 'expense' },
                                ]}
                            />
                        </div>

                        <ReactApexChart options={chartOptions} series={series} type="area" height={340} />
                    </section>

                    <section className="card p-5">
                        <div className="flex items-center justify-between gap-4">
                            <div>
                                <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Office notices</h2>
                                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Important reminders that staff should notice early in the day.
                                </p>
                            </div>
                            <Tag color="cyan" className="!mr-0 !rounded-full !border-0 !px-3 !py-1">
                                Ant Design
                            </Tag>
                        </div>

                        <div className="mt-5 space-y-3">
                            {announcements.length === 0 ? (
                                <Empty
                                    image={Empty.PRESENTED_IMAGE_SIMPLE}
                                    description="No announcements published yet."
                                />
                            ) : (
                                announcements.map((announcement) => (
                                    <div
                                        key={announcement.id}
                                        className="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 dark:border-gray-800 dark:bg-gray-900/70"
                                    >
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <div className="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {announcement.title}
                                                </div>
                                                <p className="mt-1 text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                    {announcement.content}
                                                </p>
                                            </div>
                                            {announcement.publishedAt ? (
                                                <Tooltip title={formatAdDate(announcement.publishedAt)}>
                                                    <Tag color="geekblue" className="!mr-0 !rounded-full !border-0">
                                                        Live
                                                    </Tag>
                                                </Tooltip>
                                            ) : null}
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    </section>
                </div>

                <section className="card p-5">
                    <div className="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Recent transactions</h2>
                            <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                The latest entries people are likely to review during the day.
                            </p>
                        </div>
                        <Tag color="purple" className="!mr-0 !rounded-full !border-0 !px-3 !py-1">
                            Latest {recentTransactions.length}
                        </Tag>
                    </div>

                    <div className="space-y-3">
                        {recentTransactions.length === 0 ? (
                            <Empty
                                image={Empty.PRESENTED_IMAGE_SIMPLE}
                                description="No transactions recorded yet."
                            />
                        ) : (
                            recentTransactions.map((transaction) => (
                                <a
                                    key={transaction.id}
                                    href={transaction.showUrl}
                                    className="flex flex-col gap-3 rounded-2xl border border-gray-200 p-4 transition hover:border-brand-200 hover:bg-brand-50/35 dark:border-gray-800 dark:hover:border-brand-500/30 dark:hover:bg-brand-500/10 lg:flex-row lg:items-center lg:justify-between"
                                >
                                    <div>
                                        <div className="flex flex-wrap items-center gap-3">
                                            <div className="text-sm font-semibold text-gray-900 dark:text-white">
                                                {transaction.title}
                                            </div>
                                            <Tag
                                                color={transaction.type === 'income' ? 'blue' : 'orange'}
                                                className="!mr-0 !rounded-full !border-0"
                                            >
                                                {transaction.type === 'income' ? 'Income' : 'Expense'}
                                            </Tag>
                                        </div>
                                        <div className="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                            <span>{transaction.categoryName ?? 'Uncategorized'}</span>
                                            <Tooltip title={transaction.transactionBs ? `B.S. ${transaction.transactionBs}` : 'No B.S. date saved'}>
                                                <span>{formatAdDate(transaction.transactionDate)}</span>
                                            </Tooltip>
                                        </div>
                                    </div>

                                    <div className="text-right">
                                        <div className="text-base font-semibold text-gray-900 dark:text-white">
                                            NPR {formatCurrency(transaction.amount)}
                                        </div>
                                        <div className="mt-1 text-sm text-gray-400">
                                            {transaction.transactionBs ?? 'A.D. only'}
                                        </div>
                                    </div>
                                </a>
                            ))
                        )}
                    </div>
                </section>
            </div>
        </ConfigProvider>
    );
}
