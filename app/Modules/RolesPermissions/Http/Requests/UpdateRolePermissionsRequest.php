<?php

namespace App\Modules\RolesPermissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ];
    }
}
