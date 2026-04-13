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
                'company_name' => setting('app.company_name', config('finance.setting_defaults.app.company_name')),
                'company_email' => setting('app.company_email', config('finance.setting_defaults.app.company_email')),
                'company_phone' => setting('app.company_phone', config('finance.setting_defaults.app.company_phone')),
                'address' => setting('app.address', config('finance.setting_defaults.app.address')),
                'currency_symbol' => setting('app.currency_symbol', config('finance.setting_defaults.app.currency_symbol')),
            ],
            'mail' => [
                'notification_email' => setting('mail.notification_email', config('finance.setting_defaults.mail.notification_email')),
                'send_transaction_notifications' => setting_bool('mail.send_transaction_notifications', false),
            ],
            'reports' => [
                'default_locale' => setting('reports.default_locale', 'en'),
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
}
