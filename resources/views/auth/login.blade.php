<x-guest-layout>
    <div class="mb-8">
        <div class="text-sm font-medium uppercase tracking-[0.25em] text-brand-500">Welcome back</div>
        <h1 class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">Sign in to your workspace</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Use your account to manage transactions, reports, and office finance operations.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="mt-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
            <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
            <span>Remember me on this device</span>
        </label>

        <div class="flex items-center justify-between gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-brand-500 hover:text-brand-600" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-8 text-sm text-gray-500 dark:text-gray-400">
        Need an account?
        <a href="{{ route('register') }}" class="font-medium text-brand-500 hover:text-brand-600">Create one</a>
    </p>
</x-guest-layout>
