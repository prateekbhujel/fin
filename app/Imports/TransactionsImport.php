<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TransactionsImport implements SkipsOnFailure, ToCollection, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable;
    use SkipsFailures;

    public int $importedRows = 0;

    public function __construct(protected int $userId)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $type = strtolower((string) $row['type']);
            $category = Category::firstOrCreate(
                ['name' => trim((string) $row['category']), 'type' => $type],
                ['description' => 'Imported category', 'is_active' => true]
            );

            $transactionDate = $this->transformDate($row['transaction_date']);

            Transaction::create([
                'user_id' => $this->userId,
                'category_id' => $category->id,
                'title' => trim((string) $row['title']),
                'type' => $type,
                'amount' => (float) $row['amount'],
                'transaction_date' => $transactionDate,
                'transaction_bs' => bs_date($transactionDate),
                'payment_method' => $row['payment_method'] ?: 'cash',
                'reference_no' => $row['reference_no'] ?: null,
                'notes' => $row['notes'] ?: null,
            ]);

            $this->importedRows++;
        }
    }

    public function rules(): array
    {
        return [
            '*.title' => ['required', 'string', 'max:150'],
            '*.type' => ['required', 'in:income,expense'],
            '*.category' => ['required', 'string', 'max:100'],
            '*.amount' => ['required', 'numeric', 'min:0.01'],
            '*.transaction_date' => ['required'],
            '*.payment_method' => ['nullable', 'in:'.implode(',', array_keys(config('finance.payment_methods')))],
            '*.reference_no' => ['nullable', 'string', 'max:100'],
            '*.notes' => ['nullable', 'string'],
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    protected function transformDate(mixed $value): string
    {
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
        }

        return \Carbon\Carbon::parse((string) $value)->format('Y-m-d');
    }
}
