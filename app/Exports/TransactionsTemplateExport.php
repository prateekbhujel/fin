<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsTemplateExport implements FromArray, ShouldAutoSize, WithHeadings
{
    use Exportable;

    public function array(): array
    {
        return [
            [
                'Website renewal',
                'expense',
                'Utilities',
                '4500.00',
                now()->format('Y-m-d'),
                'online',
                'WEB-2026-001',
                'Annual domain and hosting renewal',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'title',
            'type',
            'category',
            'amount',
            'transaction_date',
            'payment_method',
            'reference_no',
            'notes',
        ];
    }
}
