<?php

namespace App\Http\Requests\Modules\Documents;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => ['nullable', 'exists:transactions,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,csv', 'max:5120'],
        ];
    }
}
