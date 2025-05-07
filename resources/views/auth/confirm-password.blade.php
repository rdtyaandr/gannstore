<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Password</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            {{ __('Ini adalah area terproteksi aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Konfirmasi') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
