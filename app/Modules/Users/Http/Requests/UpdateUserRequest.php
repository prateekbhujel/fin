<?php

namespace App\Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
            'role' => ['required', Rule::exists('roles', 'name')],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ];
    }
}
