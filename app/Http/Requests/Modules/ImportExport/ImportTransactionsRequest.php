<?php

namespace App\Http\Requests\Modules\ImportExport;

use Illuminate\Foundation\Http\FormRequest;

class ImportTransactionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,xls,xlsx', 'max:5120'],
        ];
    }
}
