<?php

namespace App\Http\Controllers\Modules\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Settings\UpdateSettingRequest;
use App\Models\Setting;
use App\Support\Settings\SettingsBag;

class SettingController extends Controller
{
    public function index()
    {
        return view('modules.settings.index', [
            'settings' => [
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
            ],
        ]);
    }

    public function update(UpdateSettingRequest $request, SettingsBag $settingsBag)
    {
        $flatSettings = collect($request->validated())
            ->flatMap(function (array $groupSettings, string $group) {
                return collect($groupSettings)->map(function ($value, string $key) use ($group) {
                    return [
                        'group' => $group,
                        'key' => $group.'.'.$key,
                        'value' => is_bool($value) ? (string) (int) $value : (string) $value,
                        'type' => is_bool($value) ? 'boolean' : 'string',
                    ];
                });
            })
            ->values();

        foreach ($flatSettings as $settingRow) {
            Setting::updateOrCreate(
                ['key' => $settingRow['key']],
                $settingRow
            );
        }

        $settingsBag->flush();

        return redirect()->route('settings.index')->with('status', 'Settings updated successfully.');
    }
}
