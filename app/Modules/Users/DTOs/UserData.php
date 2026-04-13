<?php

namespace App\Modules\Users\DTOs;

readonly class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public ?string $designation,
        public bool $isActive,
        public ?string $password,
        public string $role,
    ) {
    }

    public static function fromArray(array $data, bool $isActive): self
    {
        return new self(
            name: trim((string) $data['name']),
            email: trim((string) $data['email']),
            phone: filled($data['phone'] ?? null) ? (string) $data['phone'] : null,
            designation: filled($data['designation'] ?? null) ? (string) $data['designation'] : null,
            isActive: $isActive,
            password: filled($data['password'] ?? null) ? (string) $data['password'] : null,
            role: (string) $data['role'],
        );
    }

    public function modelAttributes(bool $includePassword = true): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'is_active' => $this->isActive,
            'password' => $includePassword ? $this->password : null,
        ], static fn (mixed $value) => ! is_null($value));
    }
}
