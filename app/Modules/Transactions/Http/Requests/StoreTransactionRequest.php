<?php

namespace App\Modules\Transactions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(array_keys(config('finance.transaction_types')))],
            'category_id' => ['nullable', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['nullable', 'date'],
            'transaction_date_bs' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', Rule::in(array_keys(config('finance.payment_methods')))],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,csv', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('transaction_date') && $this->filled('transaction_date_bs')) {
            $this->merge([
                'transaction_date' => ad_date_from_bs((string) $this->input('transaction_date_bs')),
            ]);
        }
    }
}
