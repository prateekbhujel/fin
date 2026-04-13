<?php

namespace App\Modules\RolesPermissions\DTOs;

readonly class RolePermissionsData
{
    public function __construct(
        public array $permissions,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            permissions: array_values($data['permissions'] ?? []),
        );
    }
}
