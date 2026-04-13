<?php

namespace App\Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app.company_name' => ['required', 'string', 'max:150'],
            'app.company_email' => ['nullable', 'email', 'max:120'],
            'app.company_phone' => ['nullable', 'string', 'max:30'],
            'app.address' => ['nullable', 'string', 'max:255'],
            'app.currency_symbol' => ['required', 'string', 'max:10'],
            'mail.notification_email' => ['nullable', 'email', 'max:120'],
            'mail.send_transaction_notifications' => ['nullable', 'boolean'],
            'reports.default_locale' => ['required', 'in:en,np'],
        ];
    }
}
