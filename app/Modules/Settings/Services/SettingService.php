<?php

namespace App\Modules\Settings\Services;

use App\Models\Setting;
use App\Modules\Settings\DTOs\SettingsData;
use App\Support\Settings\SettingsBag;

class SettingService
{
    public function payload(): array
    {
        return [
            'app' => [
                'company_name' => setting('app.company_name', $this->default('app.company_name', 'Fin')),
                'company_email' => setting('app.company_email', $this->default('app.company_email', 'info@example.com')),
                'company_phone' => setting('app.company_phone', $this->default('app.company_phone', '+977-1-0000000')),
                'address' => setting('app.address', $this->default('app.address', 'Kathmandu, Nepal')),
                'currency_symbol' => setting('app.currency_symbol', $this->default('app.currency_symbol', 'NPR')),
            ],
            'mail' => [
                'notification_email' => setting('mail.notification_email', $this->default('mail.notification_email', 'accounts@fin.test')),
                'send_transaction_notifications' => setting_bool('mail.send_transaction_notifications', false),
            ],
            'reports' => [
                'default_locale' => setting('reports.default_locale', $this->default('reports.default_locale', 'en')),
            ],
        ];
    }

    public function update(SettingsData $data, SettingsBag $settingsBag): void
    {
        foreach ($data->rows() as $settingRow) {
            Setting::updateOrCreate(
                ['key' => $settingRow['key']],
                $settingRow
            );
        }

        $settingsBag->flush();
    }

    protected function default(string $key, mixed $fallback = null): mixed
    {
        $defaults = config('finance.setting_defaults', []);

        return $defaults[$key] ?? $fallback;
    }
}
