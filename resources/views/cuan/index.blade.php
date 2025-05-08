<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keuntungan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Total Keuntungan -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-lg shadow-lg mb-6">
                <h3 class="text-xl font-bold text-white mb-2">Total Cuan 🤑</h3>
                <div class="text-3xl font-bold text-white">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</div>
            </div>

            <!-- Riwayat Keuntungan -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Riwayat Keuntungan</h2>

                @if ($cuanData->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">Belum ada data keuntungan yang tercatat.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    <th class="p-3 text-left">No</th>
                                    <th class="p-3 text-left">Produk</th>
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Harga Jual</th>
                                    <th class="p-3 text-left">Modal</th>
                                    <th class="p-3 text-left">Keuntungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cuanData as $index => $cuan)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="p-3 dark:text-gray-300">{{ $loop->iteration }}</td>
                                        <td class="p-3 dark:text-gray-300">{{ $cuan->produk }}</td>
                                        <td class="p-3 dark:text-gray-300">{{ $cuan->tanggal }}</td>
                                        <td class="p-3 dark:text-gray-300">Rp {{ number_format($cuan->harga_jual, 0, ',', '.') }}</td>
                                        <td class="p-3 dark:text-gray-300">Rp {{ number_format($cuan->harga_beli, 0, ',', '.') }}</td>
                                        <td class="p-3 font-medium {{ $cuan->keuntungan > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            Rp {{ number_format($cuan->keuntungan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $cuanData->links() }}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
