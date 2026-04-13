<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Reset your password</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Enter your email address and we will send you a password reset link.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus class="mt-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Email Password Reset Link') }}
        </x-primary-button>
    </form>
</x-guest-layout>
