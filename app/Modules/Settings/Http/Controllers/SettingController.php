<?php

namespace App\Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\DTOs\SettingsData;
use App\Modules\Settings\Http\Requests\UpdateSettingRequest;
use App\Modules\Settings\Services\SettingService;
use App\Support\Settings\SettingsBag;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settings,
    ) {
    }

    public function index()
    {
        return view('modules.settings.index', [
            'settings' => $this->settings->payload(),
        ]);
    }

    public function update(UpdateSettingRequest $request, SettingsBag $settingsBag)
    {
        $this->settings->update(SettingsData::fromArray($request->validated()), $settingsBag);

        return redirect()->route('settings.index')->with('status', 'Settings updated successfully.');
    }
}
