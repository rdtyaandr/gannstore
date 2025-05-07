<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lupa Password</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            {{ __('Masukkan email Anda dan kami akan mengirimkan link reset password untuk memilih password baru.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="email@anda.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('Kembali ke login') }}
            </a>

            <x-primary-button>
                {{ __('Kirim Link Reset') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
