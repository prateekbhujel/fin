<?php

namespace App\Http\Requests\Modules\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
            'role' => ['required', Rule::exists('roles', 'name')],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }
}
