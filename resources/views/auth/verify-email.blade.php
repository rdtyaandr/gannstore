<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Email</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi email Anda dengan mengklik link yang baru saja kami kirimkan.') }}
        </p>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            {{ __('Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan email lainnya.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-sm rounded-md">
            {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-between mt-4 gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
