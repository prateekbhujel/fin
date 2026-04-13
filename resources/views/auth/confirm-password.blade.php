<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Confirm your password</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">This is a secure section of the application.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Confirm') }}
        </x-primary-button>
    </form>
</x-guest-layout>
