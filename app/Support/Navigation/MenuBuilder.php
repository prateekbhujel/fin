<?php

namespace App\Support\Navigation;

use App\Models\User;

class MenuBuilder
{
    public static function groups(?User $user): array
    {
        $groups = [
            [
                'title' => 'Overview',
                'items' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard', 'permission' => 'dashboard.view'],
                    ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'reports', 'permission' => 'reports.view'],
                ],
            ],
            [
                'title' => 'Finance',
                'items' => [
                    ['label' => 'Transactions', 'route' => 'transactions.index', 'icon' => 'transactions', 'permission' => 'transactions.view'],
                    ['label' => 'Categories', 'route' => 'categories.index', 'icon' => 'categories', 'permission' => 'categories.view'],
                    ['label' => 'Import & Export', 'route' => 'import-export.index', 'icon' => 'import-export', 'permission' => 'transactions.import'],
                    ['label' => 'Documents', 'route' => 'documents.index', 'icon' => 'documents', 'permission' => 'documents.view'],
                ],
            ],
            [
                'title' => 'Administration',
                'items' => [
                    ['label' => 'Users', 'route' => 'users.index', 'icon' => 'users', 'permission' => 'users.view'],
                    ['label' => 'Roles & Permissions', 'route' => 'roles-permissions.index', 'icon' => 'shield', 'permission' => 'roles_permissions.view'],
                    ['label' => 'Announcements', 'route' => 'announcements.index', 'icon' => 'megaphone', 'permission' => 'announcements.view'],
                    ['label' => 'Settings', 'route' => 'settings.index', 'icon' => 'settings', 'permission' => 'settings.view'],
                ],
            ],
        ];

        return collect($groups)
            ->map(function (array $group) use ($user) {
                $group['items'] = collect($group['items'])
                    ->filter(fn (array $item) => self::canAccess($user, $item['permission'] ?? null))
                    ->values()
                    ->all();

                return $group;
            })
            ->filter(fn (array $group) => filled($group['items']))
            ->values()
            ->all();
    }

    public static function icon(string $name): string
    {
        return match ($name) {
            'dashboard' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M4 5.75C4 4.78 4.78 4 5.75 4h4.5C11.22 4 12 4.78 12 5.75v4.5c0 .97-.78 1.75-1.75 1.75h-4.5A1.75 1.75 0 0 1 4 10.25v-4.5ZM12 13.75c0-.97.78-1.75 1.75-1.75h4.5c.97 0 1.75.78 1.75 1.75v4.5c0 .97-.78 1.75-1.75 1.75h-4.5A1.75 1.75 0 0 1 12 18.25v-4.5ZM4 13.75C4 12.78 4.78 12 5.75 12h4.5c.97 0 1.75.78 1.75 1.75v4.5c0 .97-.78 1.75-1.75 1.75h-4.5A1.75 1.75 0 0 1 4 18.25v-4.5ZM12 5.75c0-.97.78-1.75 1.75-1.75h4.5c.97 0 1.75.78 1.75 1.75v4.5c0 .97-.78 1.75-1.75 1.75h-4.5A1.75 1.75 0 0 1 12 10.25v-4.5Z" stroke="currentColor" stroke-width="1.5"/></svg>',
            'reports' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M7 17V9m5 8V5m5 12v-6M5 20h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            'transactions' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M7 7h10m0 0-2.5-2.5M17 7l-2.5 2.5M17 17H7m0 0 2.5-2.5M7 17l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'categories' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M5 7.75A1.75 1.75 0 0 1 6.75 6h3.5C11.22 6 12 6.78 12 7.75v3.5c0 .97-.78 1.75-1.75 1.75h-3.5A1.75 1.75 0 0 1 5 11.25v-3.5ZM12 12.75c0-.97.78-1.75 1.75-1.75h3.5c.97 0 1.75.78 1.75 1.75v3.5c0 .97-.78 1.75-1.75 1.75h-3.5A1.75 1.75 0 0 1 12 16.25v-3.5Z" stroke="currentColor" stroke-width="1.5"/></svg>',
            'import-export' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M12 4v10m0 0-3-3m3 3 3-3M5 16.5v.75C5 18.22 5.78 19 6.75 19h10.5c.97 0 1.75-.78 1.75-1.75v-.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'documents' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M8 7.75A1.75 1.75 0 0 1 9.75 6h4.19c.46 0 .9.18 1.22.5l2.34 2.34c.32.32.5.76.5 1.22v6.19A1.75 1.75 0 0 1 16.25 18h-6.5A1.75 1.75 0 0 1 8 16.25v-8.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M14 6v3a1 1 0 0 0 1 1h3" stroke="currentColor" stroke-width="1.5"/></svg>',
            'users' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M15 19c0-2.21-1.79-4-4-4H8a4 4 0 0 0-4 4m14-8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM9.5 11A3.5 3.5 0 1 0 9.5 4a3.5 3.5 0 0 0 0 7Zm7.5 8c0-1.83-1.25-3.37-2.94-3.82" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            'shield' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M12 3l6 2.25v5.38c0 3.82-2.43 7.22-6 8.37-3.57-1.15-6-4.55-6-8.37V5.25L12 3Z" stroke="currentColor" stroke-width="1.5"/><path d="m9.5 11.5 1.5 1.5 3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'megaphone' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M5 13.5V10.5c0-.83.67-1.5 1.5-1.5h1.67l7.33-3v12l-7.33-3H6.5A1.5 1.5 0 0 1 5 13.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M9 15.5 10.5 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            'settings' => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M10.33 4.32a1 1 0 0 1 1.34-.94l.78.33a1 1 0 0 0 .78 0l.78-.33a1 1 0 0 1 1.34.94v.86a1 1 0 0 0 .5.87l.74.43a1 1 0 0 1 .37 1.37l-.43.74a1 1 0 0 0 0 .78l.43.74a1 1 0 0 1-.37 1.37l-.74.43a1 1 0 0 0-.5.87v.86a1 1 0 0 1-1.34.94l-.78-.33a1 1 0 0 0-.78 0l-.78.33a1 1 0 0 1-1.34-.94v-.86a1 1 0 0 0-.5-.87l-.74-.43a1 1 0 0 1-.37-1.37l.43-.74a1 1 0 0 0 0-.78l-.43-.74A1 1 0 0 1 8.7 6.48l.74-.43a1 1 0 0 0 .5-.87v-.86Z" stroke="currentColor" stroke-width="1.5"/><path d="M14.5 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" stroke="currentColor" stroke-width="1.5"/></svg>',
            default => '<svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"/></svg>',
        };
    }

    protected static function canAccess(?User $user, ?string $permission): bool
    {
        if (! $permission) {
            return true;
        }

        return $user?->can($permission) ?? false;
    }
}
