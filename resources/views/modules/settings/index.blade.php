@php($title = 'Settings')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Settings" description="Configure organization identity, reporting defaults, and email notifications." />

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Organization</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="company_name" value="Company Name" />
                    <input id="company_name" name="app[company_name]" class="form-input mt-1" value="{{ old('app.company_name', $settings['app']['company_name']) }}">
                    <x-input-error :messages="$errors->get('app.company_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="company_email" value="Company Email" />
                    <input id="company_email" name="app[company_email]" type="email" class="form-input mt-1" value="{{ old('app.company_email', $settings['app']['company_email']) }}">
                </div>
                <div>
                    <x-input-label for="company_phone" value="Company Phone" />
                    <input id="company_phone" name="app[company_phone]" class="form-input mt-1" value="{{ old('app.company_phone', $settings['app']['company_phone']) }}">
                </div>
                <div>
                    <x-input-label for="currency_symbol" value="Currency Symbol" />
                    <input id="currency_symbol" name="app[currency_symbol]" class="form-input mt-1" value="{{ old('app.currency_symbol', $settings['app']['currency_symbol']) }}">
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="address" value="Address" />
                    <input id="address" name="app[address]" class="form-input mt-1" value="{{ old('app.address', $settings['app']['address']) }}">
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="notification_email" value="Notification Email" />
                    <input id="notification_email" name="mail[notification_email]" type="email" class="form-input mt-1" value="{{ old('mail.notification_email', $settings['mail']['notification_email']) }}">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" name="mail[send_transaction_notifications]" value="1" class="form-checkbox" @checked(old('mail.send_transaction_notifications', $settings['mail']['send_transaction_notifications']))>
                        Send email when a new transaction is created
                    </label>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Reports</h2>
            <div class="mt-5 max-w-sm">
                <x-input-label for="default_locale" value="Default Report Locale" />
                <select id="default_locale" name="reports[default_locale]" class="form-select mt-1">
                    <option value="en" @selected(old('reports.default_locale', $settings['reports']['default_locale']) === 'en')>English</option>
                    <option value="np" @selected(old('reports.default_locale', $settings['reports']['default_locale']) === 'np')>Nepali</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button class="btn-primary" type="submit">Save Settings</button>
        </div>
    </form>
@endsection
