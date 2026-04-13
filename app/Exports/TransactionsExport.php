<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(protected array $filters = [])
    {
    }

    public function collection()
    {
        return Transaction::query()
            ->with(['category', 'user'])
            ->filter($this->filters)
            ->latest('transaction_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Type',
            'Category',
            'Amount',
            'Transaction Date (AD)',
            'Transaction Date (BS)',
            'Payment Method',
            'Reference No',
            'Created By',
            'Notes',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->title,
            ucfirst($transaction->type),
            $transaction->category?->name,
            (float) $transaction->amount,
            optional($transaction->transaction_date)->format('Y-m-d'),
            $transaction->transaction_bs,
            config('finance.payment_methods.'.$transaction->payment_method, $transaction->payment_method),
            $transaction->reference_no,
            $transaction->user?->name,
            $transaction->notes,
        ];
    }
}
