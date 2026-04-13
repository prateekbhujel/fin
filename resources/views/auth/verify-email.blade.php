<x-guest-layout>
    <div class="space-y-5">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Verify your email</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Before continuing, please verify your email address from the link we sent you.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="flex items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
