<?php

namespace App\Modules\Reports\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function filteredQuery(array $filters = []): Builder
    {
        return Transaction::query()->with(['category', 'user'])->filter($filters);
    }

    public function overview(array $filters = []): array
    {
        $query = $this->filteredQuery($filters);
        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expense = (clone $query)->where('type', 'expense')->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'transactions_count' => (clone $query)->count(),
        ];
    }

    public function monthlySummary(array $filters = []): Collection
    {
        $periodExpression = $this->monthPeriodExpression();

        return $this->filteredQuery($filters)
            ->selectRaw("{$periodExpression} as period")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income_total")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense_total")
            ->groupBy(DB::raw($periodExpression))
            ->orderBy(DB::raw($periodExpression))
            ->get();
    }

    public function yearlySummary(array $filters = []): Collection
    {
        $periodExpression = $this->yearPeriodExpression();

        return $this->filteredQuery($filters)
            ->selectRaw("{$periodExpression} as period")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income_total")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense_total")
            ->groupBy(DB::raw($periodExpression))
            ->orderBy(DB::raw($periodExpression))
            ->get();
    }

    public function categorySummary(array $filters = []): Collection
    {
        return $this->filteredQuery($filters)
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', 'transactions.type')
            ->selectRaw('SUM(transactions.amount) as total_amount')
            ->selectRaw('COUNT(transactions.id) as total_entries')
            ->groupBy('categories.name', 'transactions.type')
            ->orderByDesc('total_amount')
            ->get();
    }

    public function monthlyTrend(int $months = 6): array
    {
        $startDate = now()->startOfMonth()->subMonths($months - 1);
        $periodExpression = $this->monthPeriodExpression();

        $rows = Transaction::query()
            ->whereDate('transaction_date', '>=', $startDate)
            ->selectRaw("{$periodExpression} as period")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income_total")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense_total")
            ->groupBy(DB::raw($periodExpression))
            ->orderBy(DB::raw($periodExpression))
            ->get();

        return [
            'labels' => $rows->map(function ($row) {
                return Carbon::createFromFormat('Y-m', $row->period)->format('M Y');
            })->all(),
            'income' => $rows->pluck('income_total')->map(fn ($value) => (float) $value)->all(),
            'expense' => $rows->pluck('expense_total')->map(fn ($value) => (float) $value)->all(),
        ];
    }

    protected function monthPeriodExpression(): string
    {
        return match ($this->driver()) {
            'sqlite' => "strftime('%Y-%m', transaction_date)",
            'pgsql' => "TO_CHAR(transaction_date, 'YYYY-MM')",
            'sqlsrv' => "FORMAT(transaction_date, 'yyyy-MM')",
            default => "DATE_FORMAT(transaction_date, '%Y-%m')",
        };
    }

    protected function yearPeriodExpression(): string
    {
        return match ($this->driver()) {
            'sqlite' => "strftime('%Y', transaction_date)",
            'pgsql' => "TO_CHAR(transaction_date, 'YYYY')",
            'sqlsrv' => "FORMAT(transaction_date, 'yyyy')",
            default => 'YEAR(transaction_date)',
        };
    }

    protected function driver(): string
    {
        return Transaction::query()->getConnection()->getDriverName();
    }
}
