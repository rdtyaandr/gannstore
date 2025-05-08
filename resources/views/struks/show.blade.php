<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                {{ __('Detail Struk') }}
            </h2>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700 flex items-center shadow-md transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Struk
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="font-medium text-sm text-gray-700 dark:text-gray-300">Cetak Struk</span>
                        </div>

                        <button onclick="cetakStrukTerang()" class="block w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Cetak Mode Terang
                            </div>
                        </button>

                        <button onclick="cetakStrukGelap()" class="block w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                Cetak Mode Gelap
                            </div>
                        </button>

                        <div class="px-4 py-2 border-t border-b border-gray-100 dark:border-gray-700">
                            <span class="font-medium text-sm text-gray-700 dark:text-gray-300">Simpan Struk</span>
                        </div>

                        <button onclick="simpanGambarTerang()" class="block w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Simpan Gambar Terang
                            </div>
                        </button>

                        <button onclick="simpanGambarGelap()" class="block w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Simpan Gambar Gelap
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <!-- Untuk Diprint -->
                    <div id="printable-area" class="mx-auto max-w-lg">
                        <!-- Header Transaksi dengan Efek Gradien -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-8 rounded-lg shadow-lg mb-6 transform transition-all duration-300 hover:shadow-xl">
                            <div class="text-center">
                                <!-- Logo dan Nama Toko -->
                                <div class="flex justify-center items-center mb-4">
                                    <img src="{{ asset('images/logo.png') }}" alt="GannStore Logo" class="h-16 w-auto mr-3 drop-shadow-md">
                                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">GannStore</h1>
                                </div>

                                <h2 class="text-xl font-semibold mb-3 text-blue-100">Rincian Transaksi</h2>
                                <div class="flex items-center justify-center space-x-2 bg-white/20 backdrop-blur-sm rounded-full py-2 px-4 inline-block border border-white/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $struk->getValue('tanggal') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Utama -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                            <!-- Produk dan Harga - Bagian Paling Penting -->
                            <div class="bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-800 p-6 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg mr-3">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-lg text-gray-700 dark:text-gray-300">Produk</span>
                                    </div>
                                    <span class="text-lg bg-gray-100 dark:bg-gray-700 py-1 px-3 rounded-lg dark:text-gray-300">{{ $struk->getValue('produk') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg mr-3">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-lg text-gray-700 dark:text-gray-300">Harga</span>
                                    </div>
                                    <span class="text-lg font-bold bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-400 py-1 px-3 rounded-lg">
                                        <?php
                                            // Hapus karakter non-angka, pastikan yang ditampilkan adalah angka saja
                                            $harga = preg_replace('/[^0-9]/', '', $struk->getValue('harga'));
                                            // Jika tidak ada angka, tampilkan 0
                                            if (empty($harga)) $harga = "0";
                                            // Format dengan pemisah ribuan
                                            echo 'Rp ' . number_format(intval($harga), 0, ',', '.');
                                        ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Detail Transaksi Lainnya -->
                            <div class="p-6 space-y-4">
                                @php
                                    // Simpan semua field yang sudah ditampilkan
                                    $displayedFields = [
                                        'produk', 'harga', 'tanggal'
                                    ];
                                @endphp

                                @if($struk->getValue('id_transaksi'))
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 dark:bg-blue-900 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 dark:text-gray-400">ID Transaksi</span>
                                    </div>
                                    <span class="font-medium dark:text-gray-300">{{ $struk->getValue('id_transaksi') }}</span>
                                </div>
                                @php $displayedFields[] = 'id_transaksi'; @endphp
                                @endif

                                @if($struk->getValue('nomor_hp'))
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 dark:bg-blue-900 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 dark:text-gray-400">Nomor HP</span>
                                    </div>
                                    <span class="font-medium dark:text-gray-300">{{ $struk->getValue('nomor_hp') }}</span>
                                </div>
                                @php $displayedFields[] = 'nomor_hp'; @endphp
                                @endif

                                @if($struk->getValue('pembayaran'))
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 dark:bg-blue-900 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 dark:text-gray-400">Pembayaran</span>
                                    </div>
                                    <span class="font-medium dark:text-gray-300">{{ $struk->getValue('pembayaran') }}</span>
                                </div>
                                @php $displayedFields[] = 'pembayaran'; @endphp
                                @endif

                                @if($struk->getValue('status'))
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 dark:bg-blue-900 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 dark:text-gray-400">Status</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full font-medium text-sm {{ $struk->getValue('status') == 'SUKSES' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }} shadow-sm">
                                        {{ $struk->getValue('status') }}
                                    </span>
                                </div>
                                @php $displayedFields[] = 'status'; @endphp
                                @endif

                                @if($struk->getValue('sn_ref'))
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 dark:bg-blue-900 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 dark:text-gray-400">SN/Ref</span>
                                    </div>
                                    <span class="font-medium text-right dark:text-gray-300">{{ Str::limit($struk->getValue('sn_ref'), 15, '...') }}</span>
                                </div>
                                @php $displayedFields[] = 'sn_ref'; @endphp
                                @endif

                                <!-- Tambahkan field lain jika ada -->
                                @foreach($struk->data as $label => $value)
                                    @php
                                        // Hanya field wajib yang perlu difilter dari duplikasi
                                        $wajibFields = ['produk', 'harga', 'tanggal'];

                                        // Decode dan bersihkan label
                                        $decodedLabel = is_string($label) ? $label : '';
                                        $iterations = 0;
                                        $maxIterations = 5;

                                        // Decode URL-encoded label
                                        while (strpos($decodedLabel, '%') !== false && $iterations < $maxIterations) {
                                            $newLabel = urldecode($decodedLabel);
                                            if ($newLabel === $decodedLabel) break;
                                            $decodedLabel = $newLabel;
                                            $iterations++;
                                        }

                                        // Bersihkan label
                                        $cleanedLabel = trim(preg_replace('/\s+/', ' ', $decodedLabel));
                                        $cleanedLabel = preg_replace('/[*\s]+$/', '', $cleanedLabel); // Hapus tanda bintang di akhir
                                        $labelLower = strtolower($cleanedLabel);

                                        // Decode nilai
                                        $decodedValue = is_string($value) ? $value : '';
                                        $iterations = 0;

                                        // Decode URL-encoded value
                                        while (strpos($decodedValue, '%') !== false && $iterations < $maxIterations) {
                                            $newValue = urldecode($decodedValue);
                                            if ($newValue === $decodedValue) break;
                                            $decodedValue = $newValue;
                                            $iterations++;
                                        }

                                        // Bersihkan nilai dari karakter newline
                                        $decodedValue = str_replace(["\r\n", "\n", "\r"], " ", $decodedValue);

                                        // Periksa apakah ini field wajib yang perlu difilter
                                        $shouldSkip = false;
                                        foreach ($wajibFields as $field) {
                                            if (str_contains($labelLower, $field)) {
                                                $shouldSkip = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if(!empty($decodedValue) && !$shouldSkip)
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors">
                                        <div class="flex items-center">
                                            <div class="bg-blue-50 dark:bg-blue-900 p-1.5 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $cleanedLabel }}</span>
                                        </div>
                                        <span class="font-medium text-right dark:text-gray-300">{{ Str::limit($decodedValue, 15, '...') }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Footer Tanda Terima -->
                        <div class="text-center mt-8 pt-4">
                            <div class="inline-block border-2 border-dashed border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4 bg-blue-50/50 dark:bg-blue-900/30">
                                <svg class="w-8 h-8 text-green-500 dark:text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700 dark:text-gray-300 font-medium">Transaksi Berhasil</p>
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 font-medium">Terima kasih telah bertransaksi di</p>
                            <div class="flex justify-center items-center mt-2 mb-3">
                                <img src="{{ asset('images/logo.png') }}" alt="GannStore Logo" class="h-7 w-auto mr-2 drop-shadow-sm">
                                <p class="text-blue-600 dark:text-blue-400 font-bold text-lg">GannStore</p>
                            </div>
                            <p class="text-gray-500 dark:text-gray-500 text-sm">Kunjungi kami kembali</p>
                            <p class="text-gray-400 dark:text-gray-600 text-xs mt-2">{{ $struk->getValue('tanggal') }}</p>
                        </div>
                    </div>

                    <!-- Tombol navigasi non-printable -->
                    <div class="mt-8 flex justify-end print:hidden">
                        <a href="{{ route('dashboard') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-lg shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 mr-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <a href="{{ route('struks.edit', $struk) }}" class="bg-blue-500 dark:bg-blue-600 text-white px-5 py-2 rounded-lg shadow-sm hover:bg-blue-600 dark:hover:bg-blue-700 transition-all duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15.414a2 2 0 01-2.828 0l-4.243-4.243a2 2 0 010-2.828 2 2 0 012.828 0l4.243 4.243"></path>
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print function -->
    @push('scripts')
    <!-- Library yang diperlukan untuk ekspor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Fungsi untuk cetak struk dalam mode terang
        function cetakStrukTerang() {
            // Clone area yang akan dicetak
            const printContents = document.getElementById('printable-area').cloneNode(true);
            const originalContents = document.body.innerHTML;

            // Hapus kelas dark dan terapkan style terang
            printContents.classList.remove('dark');

            // Ubah semua elemen yang memiliki warna dark mode menjadi light mode
            const allElements = printContents.querySelectorAll('*');
            allElements.forEach(el => {
                // Hapus dark classes
                Array.from(el.classList).forEach(cls => {
                    if (cls.startsWith('dark:')) {
                        el.classList.remove(cls);
                    }
                });

                // Ubah warna background element
                if (el.classList.contains('bg-white') || el.classList.contains('bg-gray-50') || el.classList.contains('bg-gray-100')) {
                    el.style.backgroundColor = 'white';
                }
                if (el.classList.contains('bg-blue-50')) {
                    el.style.backgroundColor = '#eff6ff'; // blue-50
                }
                if (el.classList.contains('bg-green-100')) {
                    el.style.backgroundColor = '#dcfce7'; // green-100
                }
                if (el.classList.contains('bg-blue-100')) {
                    el.style.backgroundColor = '#dbeafe'; // blue-100
                }

                // Ubah warna text
                if (el.classList.contains('text-gray-700') || el.classList.contains('text-gray-800') || el.classList.contains('text-gray-900')) {
                    el.style.color = '#111827'; // gray-900
                }
                if (el.classList.contains('text-gray-600')) {
                    el.style.color = '#4b5563'; // gray-600
                }
                if (el.classList.contains('text-gray-400')) {
                    el.style.color = '#9ca3af'; // gray-400
                }
                if (el.classList.contains('text-blue-600')) {
                    el.style.color = '#2563eb'; // blue-600
                }
                if (el.classList.contains('text-blue-500')) {
                    el.style.color = '#3b82f6'; // blue-500
                }
                if (el.classList.contains('text-green-700')) {
                    el.style.color = '#15803d'; // green-700
                }
                if (el.classList.contains('text-green-600')) {
                    el.style.color = '#16a34a'; // green-600
                }
                if (el.classList.contains('text-white')) {
                    el.style.color = '#ffffff'; // white
                }

                // SVG handling
                if (el.tagName === 'svg') {
                    el.style.display = 'inline-block';
                    el.setAttribute('fill', 'none');

                    // Atur warna stroke berdasarkan parent atau class
                    if (el.classList.contains('text-blue-600') || el.parentElement?.classList.contains('text-blue-600')) {
                        el.setAttribute('stroke', '#2563eb'); // blue-600
                    } else if (el.classList.contains('text-blue-500') || el.parentElement?.classList.contains('text-blue-500')) {
                        el.setAttribute('stroke', '#3b82f6'); // blue-500
                    } else if (el.classList.contains('text-green-600') || el.parentElement?.classList.contains('text-green-600')) {
                        el.setAttribute('stroke', '#16a34a'); // green-600
                    } else if (el.classList.contains('text-green-500') || el.parentElement?.classList.contains('text-green-500')) {
                        el.setAttribute('stroke', '#22c55e'); // green-500
                    } else if (el.classList.contains('text-white') || el.parentElement?.classList.contains('text-white')) {
                        el.setAttribute('stroke', '#ffffff'); // white
                    } else {
                        el.setAttribute('stroke', '#4b5563'); // gray-600 default
                    }
                }

                // Perbaiki border
                if (el.classList.contains('border-gray-100')) {
                    el.style.borderColor = '#f3f4f6'; // gray-100
                }
                if (el.classList.contains('border-blue-200')) {
                    el.style.borderColor = '#bfdbfe'; // blue-200
                }
                if (el.classList.contains('border-white')) {
                    el.style.borderColor = '#ffffff'; // white
                }

                // Pastikan border dashed tetap dashed
                if (el.classList.contains('border-dashed')) {
                    el.style.borderStyle = 'dashed';
                }
            });

            // Perbaiki khusus untuk efek gradient dan elemen tertentu
            const gradientEl = printContents.querySelector('.bg-gradient-to-r');
            if (gradientEl) {
                gradientEl.style.background = 'linear-gradient(to right, #3b82f6, #4f46e5)';
                gradientEl.style.color = '#ffffff';
                const gradientChilds = gradientEl.querySelectorAll('*');
                gradientChilds.forEach(child => {
                    child.style.color = '#ffffff';
                    if (child.tagName === 'svg') {
                        child.setAttribute('stroke', '#ffffff');
                    }
                });
            }

            // Buat style untuk print
            const printStyles = `
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            width: 100%;
                            max-width: 80mm;
                            margin: 0 auto;
                            padding: 5mm;
                            background-color: white !important;
                            color: black !important;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            color-adjust: exact !important;
                        }
                        * {
                            box-sizing: border-box;
                        }

                        /* Pengecualian untuk gradien dan elemen tertentu */
                        .bg-gradient-to-r {
                            background: linear-gradient(to right, #3b82f6, #4f46e5) !important;
                            color: white !important;
                        }
                        .bg-gradient-to-r * {
                            background-color: transparent !important;
                            color: white !important;
                        }
                        .bg-gradient-to-r svg {
                            stroke: white !important;
                        }

                        .bg-blue-100 {
                            background-color: #dbeafe !important;
                        }
                        .bg-blue-50 {
                            background-color: #eff6ff !important;
                        }
                        .bg-green-100 {
                            background-color: #dcfce7 !important;
                        }
                        .bg-white, .bg-gray-50, .bg-gray-100 {
                            background-color: white !important;
                        }

                        .text-blue-600, .text-blue-500, .text-blue-400 {
                            color: #2563eb !important; /* blue-600 */
                        }
                        .text-green-600, .text-green-500, .text-green-400 {
                            color: #16a34a !important; /* green-600 */
                        }
                        .text-green-700 {
                            color: #15803d !important; /* green-700 */
                        }
                        .text-gray-900, .text-gray-800, .text-gray-700 {
                            color: #111827 !important; /* gray-900 */
                        }
                        .text-gray-600 {
                            color: #4b5563 !important; /* gray-600 */
                        }
                        .text-gray-400 {
                            color: #9ca3af !important; /* gray-400 */
                        }
                        .text-white {
                            color: white !important;
                        }

                        .border-gray-100 {
                            border-color: #f3f4f6 !important; /* gray-100 */
                        }
                        .border-blue-200 {
                            border-color: #bfdbfe !important; /* blue-200 */
                        }
                        .border-dashed {
                            border-style: dashed !important;
                        }

                        /* Perbaikan border dashed */
                        .border-dashed {
                            border-style: dashed !important;
                        }

                        h1, h2 {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            text-align: center;
                        }
                        img {
                            display: inline-block;
                            max-height: 40px;
                            width: auto;
                        }
                        svg {
                            display: inline-block !important;
                        }
                        .flex {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 8px;
                            border-bottom: 1px dotted #ddd;
                            padding-bottom: 5px;
                        }
                        .justify-center {
                            justify-content: center;
                        }
                        .items-center {
                            align-items: center;
                        }
                        .font-medium, .font-semibold, .font-bold {
                            font-weight: bold;
                        }
                        .text-lg, .text-xl, .text-2xl, .text-3xl {
                            font-size: 14px;
                        }
                        .text-sm, .text-xs {
                            font-size: 12px;
                        }
                        .text-center {
                            text-align: center;
                        }
                        .mt-8, .mt-2, .mb-3, .mb-4 {
                            margin-top: 15px;
                        }
                        .mr-2, .mr-3 {
                            margin-right: 5px;
                        }
                        .space-y-4 > * {
                            margin-bottom: 8px;
                        }
                        .rounded-lg, .rounded-full, .rounded-xl {
                            border-radius: 4px;
                        }
                        .p-6, .p-8, .p-4, .py-2, .px-4 {
                            padding: 8px;
                        }
                        .inline-block {
                            display: inline-block;
                        }
                    }
                </style>
            `;

            // Ganti isi document untuk print
            document.body.innerHTML = printStyles + printContents.outerHTML;

            // Proses cetak
            window.print();

            // Kembalikan isi document seperti semula
            document.body.innerHTML = originalContents;
        }

        // Fungsi untuk cetak struk dalam mode gelap
        function cetakStrukGelap() {
            // Clone area yang akan dicetak
            const printContents = document.getElementById('printable-area').cloneNode(true);
            const originalContents = document.body.innerHTML;

            // Tambahkan kelas dark dan terapkan style gelap
            printContents.classList.add('dark');

            // Ubah semua elemen menjadi dark mode
            const allElements = printContents.querySelectorAll('*');
            allElements.forEach(el => {
                // Background
                if (el.classList.contains('bg-white') || el.classList.contains('bg-gray-50') || el.classList.contains('bg-gray-100')) {
                    el.style.backgroundColor = '#1f2937'; // gray-800
                }
                if (el.classList.contains('dark:bg-gray-800')) {
                    el.style.backgroundColor = '#1f2937'; // gray-800
                }
                if (el.classList.contains('dark:bg-gray-700')) {
                    el.style.backgroundColor = '#374151'; // gray-700
                }
                if (el.classList.contains('dark:bg-blue-900')) {
                    el.style.backgroundColor = '#1e3a8a'; // blue-900
                }
                if (el.classList.contains('dark:bg-green-900')) {
                    el.style.backgroundColor = '#14532d'; // green-900
                }
                if (el.classList.contains('dark:bg-blue-800')) {
                    el.style.backgroundColor = '#1e40af'; // blue-800
                }

                // Text
                if (el.classList.contains('text-gray-700') || el.classList.contains('text-gray-800') || el.classList.contains('text-gray-900')) {
                    el.style.color = '#e5e7eb'; // gray-200
                }
                if (el.classList.contains('dark:text-gray-300')) {
                    el.style.color = '#d1d5db'; // gray-300
                }
                if (el.classList.contains('dark:text-gray-400')) {
                    el.style.color = '#9ca3af'; // gray-400
                }
                if (el.classList.contains('dark:text-gray-200')) {
                    el.style.color = '#e5e7eb'; // gray-200
                }
                if (el.classList.contains('dark:text-blue-400')) {
                    el.style.color = '#60a5fa'; // blue-400
                }
                if (el.classList.contains('dark:text-green-400')) {
                    el.style.color = '#4ade80'; // green-400
                }
                if (el.classList.contains('text-white')) {
                    el.style.color = '#ffffff'; // white
                }

                // Border
                if (el.classList.contains('dark:border-gray-700')) {
                    el.style.borderColor = '#374151'; // gray-700
                }
                if (el.classList.contains('dark:border-blue-800')) {
                    el.style.borderColor = '#1e40af'; // blue-800
                }
                if (el.classList.contains('border-white')) {
                    el.style.borderColor = '#ffffff'; // white
                }

                // SVG handling
                if (el.tagName === 'svg') {
                    el.style.display = 'inline-block';
                    el.setAttribute('fill', 'none');

                    // Atur warna stroke berdasarkan parent atau class
                    if (el.classList.contains('dark:text-blue-400') || el.parentElement?.classList.contains('dark:text-blue-400')) {
                        el.setAttribute('stroke', '#60a5fa'); // blue-400
                    } else if (el.classList.contains('dark:text-green-400') || el.parentElement?.classList.contains('dark:text-green-400')) {
                        el.setAttribute('stroke', '#4ade80'); // green-400
                    } else if (el.classList.contains('text-white') || el.parentElement?.classList.contains('text-white')) {
                        el.setAttribute('stroke', '#ffffff'); // white
                    } else {
                        el.setAttribute('stroke', '#d1d5db'); // gray-300 default
                    }
                }
            });

            // Perbaiki khusus untuk efek gradient dan elemen tertentu
            const gradientEl = printContents.querySelector('.bg-gradient-to-r');
            if (gradientEl) {
                gradientEl.style.background = 'linear-gradient(to right, #3b82f6, #4f46e5)';
                gradientEl.style.color = '#ffffff';
                const gradientChilds = gradientEl.querySelectorAll('*');
                gradientChilds.forEach(child => {
                    child.style.color = '#ffffff';
                    if (child.tagName === 'svg') {
                        child.setAttribute('stroke', '#ffffff');
                    }
                });
            }

            // Buat style untuk print
            const printStyles = `
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            width: 100%;
                            max-width: 80mm;
                            margin: 0 auto;
                            padding: 5mm;
                            background-color: #1f2937 !important;
                            color: #e5e7eb !important;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            color-adjust: exact !important;
                        }
                        * {
                            box-sizing: border-box;
                        }

                        /* Pengecualian untuk gradien dan elemen tertentu */
                        .bg-gradient-to-r {
                            background: linear-gradient(to right, #3b82f6, #4f46e5) !important;
                            color: white !important;
                        }
                        .bg-gradient-to-r * {
                            background-color: transparent !important;
                            color: white !important;
                        }
                        .bg-gradient-to-r svg {
                            stroke: white !important;
                        }

                        .bg-white, .bg-gray-50, .bg-gray-100, .dark\\:bg-gray-800 {
                            background-color: #1f2937 !important; /* gray-800 */
                        }
                        .dark\\:bg-gray-700 {
                            background-color: #374151 !important; /* gray-700 */
                        }
                        .dark\\:bg-blue-900 {
                            background-color: #1e3a8a !important; /* blue-900 */
                        }
                        .dark\\:bg-green-900 {
                            background-color: #14532d !important; /* green-900 */
                        }
                        .dark\\:bg-blue-800 {
                            background-color: #1e40af !important; /* blue-800 */
                        }

                        .text-white {
                            color: white !important;
                        }
                        .text-gray-900, .text-gray-800, .text-gray-700, .dark\\:text-gray-200 {
                            color: #e5e7eb !important; /* gray-200 */
                        }
                        .dark\\:text-gray-300 {
                            color: #d1d5db !important; /* gray-300 */
                        }
                        .dark\\:text-gray-400, .text-gray-600 {
                            color: #9ca3af !important; /* gray-400 */
                        }
                        .dark\\:text-blue-400 {
                            color: #60a5fa !important; /* blue-400 */
                        }
                        .dark\\:text-green-400 {
                            color: #4ade80 !important; /* green-400 */
                        }

                        .dark\\:border-gray-700 {
                            border-color: #374151 !important; /* gray-700 */
                        }
                        .dark\\:border-blue-800 {
                            border-color: #1e40af !important; /* blue-800 */
                        }

                        /* Perbaikan border dashed */
                        .border-dashed {
                            border-style: dashed !important;
                        }

                        h1, h2 {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            text-align: center;
                            color: #e5e7eb !important;
                        }
                        img {
                            display: inline-block;
                            max-height: 40px;
                            width: auto;
                        }
                        svg {
                            display: inline-block !important;
                        }
                        .flex {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 8px;
                            border-bottom: 1px dotted #4b5563;
                            padding-bottom: 5px;
                        }
                        .justify-center {
                            justify-content: center;
                        }
                        .items-center {
                            align-items: center;
                        }
                        .font-medium, .font-semibold, .font-bold {
                            font-weight: bold;
                        }
                        .text-lg, .text-xl, .text-2xl, .text-3xl {
                            font-size: 14px;
                        }
                        .text-sm, .text-xs {
                            font-size: 12px;
                        }
                        .text-center {
                            text-align: center;
                        }
                        .mt-8, .mt-2, .mb-3, .mb-4 {
                            margin-top: 15px;
                        }
                        .mr-2, .mr-3 {
                            margin-right: 5px;
                        }
                        .space-y-4 > * {
                            margin-bottom: 8px;
                        }
                        .rounded-lg, .rounded-full, .rounded-xl {
                            border-radius: 4px;
                        }
                        .p-6, .p-8, .p-4, .py-2, .px-4 {
                            padding: 8px;
                        }
                        .inline-block {
                            display: inline-block;
                        }
                    }
                </style>
            `;

            // Ganti isi document untuk print
            document.body.innerHTML = printStyles + printContents.outerHTML;

            // Proses cetak
            window.print();

            // Kembalikan isi document seperti semula
            document.body.innerHTML = originalContents;
        }

        // Fungsi untuk simpan gambar terang
        function simpanGambarTerang() {
            // Tampilkan indikator loading
            showLoadingIndicator("Membuat gambar terang...");

            // Siapkan konten struk dalam mode terang
            const container = document.createElement('div');
            container.style.width = '500px';
            container.style.padding = '20px';
            container.style.backgroundColor = 'white';
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.top = '-9999px';

            // Clone struk dan terapkan mode terang
            const strukContent = document.getElementById('printable-area').cloneNode(true);
            strukContent.classList.remove('dark');

            // Ubah semua elemen menjadi light mode
            const allElements = strukContent.querySelectorAll('*');
            allElements.forEach(el => {
                // Hapus dark classes
                Array.from(el.classList).forEach(cls => {
                    if (cls.startsWith('dark:')) {
                        el.classList.remove(cls);
                    }
                });

                // Ubah warna background element
                if (el.classList.contains('bg-white') || el.classList.contains('bg-gray-50') || el.classList.contains('bg-gray-100')) {
                    el.style.backgroundColor = 'white';
                }
                if (el.classList.contains('bg-blue-50')) {
                    el.style.backgroundColor = '#eff6ff'; // blue-50
                }
                if (el.classList.contains('bg-green-100')) {
                    el.style.backgroundColor = '#dcfce7'; // green-100
                }
                if (el.classList.contains('bg-blue-100')) {
                    el.style.backgroundColor = '#dbeafe'; // blue-100
                }

                // Ubah warna text
                if (el.classList.contains('text-gray-700') || el.classList.contains('text-gray-800') || el.classList.contains('text-gray-900')) {
                    el.style.color = '#111827'; // gray-900
                }
                if (el.classList.contains('text-gray-600')) {
                    el.style.color = '#4b5563'; // gray-600
                }
                if (el.classList.contains('text-gray-400')) {
                    el.style.color = '#9ca3af'; // gray-400
                }
                if (el.classList.contains('text-blue-600')) {
                    el.style.color = '#2563eb'; // blue-600
                }
                if (el.classList.contains('text-blue-500')) {
                    el.style.color = '#3b82f6'; // blue-500
                }
                if (el.classList.contains('text-green-700')) {
                    el.style.color = '#15803d'; // green-700
                }
                if (el.classList.contains('text-green-600')) {
                    el.style.color = '#16a34a'; // green-600
                }
                if (el.classList.contains('text-white')) {
                    el.style.color = '#ffffff'; // white
                }

                // SVG handling
                if (el.tagName === 'svg') {
                    el.style.display = 'inline-block';
                    el.setAttribute('fill', 'none');

                    // Atur warna stroke berdasarkan parent atau class
                    if (el.classList.contains('text-blue-600') || el.parentElement?.classList.contains('text-blue-600')) {
                        el.setAttribute('stroke', '#2563eb'); // blue-600
                    } else if (el.classList.contains('text-blue-500') || el.parentElement?.classList.contains('text-blue-500')) {
                        el.setAttribute('stroke', '#3b82f6'); // blue-500
                    } else if (el.classList.contains('text-green-600') || el.parentElement?.classList.contains('text-green-600')) {
                        el.setAttribute('stroke', '#16a34a'); // green-600
                    } else if (el.classList.contains('text-green-500') || el.parentElement?.classList.contains('text-green-500')) {
                        el.setAttribute('stroke', '#22c55e'); // green-500
                    } else if (el.classList.contains('text-white') || el.parentElement?.classList.contains('text-white')) {
                        el.setAttribute('stroke', '#ffffff'); // white
                    } else {
                        el.setAttribute('stroke', '#4b5563'); // gray-600 default
                    }
                }

                // Perbaiki border
                if (el.classList.contains('border-gray-100')) {
                    el.style.borderColor = '#f3f4f6'; // gray-100
                }
                if (el.classList.contains('border-blue-200')) {
                    el.style.borderColor = '#bfdbfe'; // blue-200
                }
                if (el.classList.contains('border-white')) {
                    el.style.borderColor = '#ffffff'; // white
                }

                // Pastikan border dashed tetap dashed
                if (el.classList.contains('border-dashed')) {
                    el.style.borderStyle = 'dashed';
                }
            });

            // Perbaiki khusus untuk efek gradient dan elemen tertentu
            const gradientEl = strukContent.querySelector('.bg-gradient-to-r');
            if (gradientEl) {
                gradientEl.style.background = 'linear-gradient(to right, #3b82f6, #4f46e5)';
                gradientEl.style.color = '#ffffff';
                const gradientChilds = gradientEl.querySelectorAll('*');
                gradientChilds.forEach(child => {
                    child.style.color = '#ffffff';
                    if (child.tagName === 'svg') {
                        child.setAttribute('stroke', '#ffffff');
                    }
                });
            }

            container.appendChild(strukContent);
            document.body.appendChild(container);

            // Tunggu sebentar agar DOM dirender
            setTimeout(() => {
                // Gunakan html2canvas untuk mengambil gambar
                html2canvas(container, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    onclone: function(clonedDoc) {
                        const clonedElement = clonedDoc.querySelector('#printable-area');
                        if (clonedElement) {
                            // Pastikan semua SVG terlihat
                            const svgs = clonedElement.querySelectorAll('svg');
                            svgs.forEach(svg => {
                                svg.style.display = 'inline-block';

                                // Atur warna stroke berdasarkan parent
                                const parent = svg.parentElement;
                                if (parent.classList.contains('text-blue-600') || parent.style.color === '#2563eb') {
                                    svg.setAttribute('stroke', '#2563eb'); // blue-600
                                } else if (parent.classList.contains('text-green-500') || parent.style.color === '#22c55e') {
                                    svg.setAttribute('stroke', '#22c55e'); // green-500
                                } else if (parent.classList.contains('text-green-600') || parent.style.color === '#16a34a') {
                                    svg.setAttribute('stroke', '#16a34a'); // green-600
                                } else if (parent.classList.contains('text-white') || parent.style.color === '#ffffff') {
                                    svg.setAttribute('stroke', '#ffffff'); // white
                                } else {
                                    svg.setAttribute('stroke', '#4b5563'); // gray-600 default
                                }
                            });

                            // Perbaiki warna elemen tertentu
                            const transaksiElementSuccess = clonedElement.querySelector('.text-green-500');
                            if (transaksiElementSuccess) {
                                transaksiElementSuccess.style.color = '#22c55e'; // green-500
                                const svgInSuccess = transaksiElementSuccess.querySelector('svg');
                                if (svgInSuccess) {
                                    svgInSuccess.setAttribute('stroke', '#22c55e');
                                }

                            }
                        }
                    }
                }).then(canvas => {
                    try {
                        // Konversi canvas ke URL data dengan format PNG untuk kualitas terbaik
                        const imgData = canvas.toDataURL('image/png');

                        // Buat link download
                        const link = document.createElement('a');
                        link.href = imgData;
                        link.download = 'struk-gannstore-terang-' + new Date().getTime() + '.png';
                        link.click();

                        // Bersihkan
                        document.body.removeChild(container);
                        hideLoadingIndicator();
                    } catch (e) {
                        console.error('Terjadi kesalahan saat ekspor gambar terang:', e);
                        alert('Terjadi kesalahan saat ekspor gambar. Silakan coba lagi.');
                        document.body.removeChild(container);
                        hideLoadingIndicator();
                    }
                }).catch(err => {
                    console.error('Terjadi kesalahan pada html2canvas:', err);
                    alert('Terjadi kesalahan saat mengambil gambar. Silakan coba lagi.');
                    document.body.removeChild(container);
                    hideLoadingIndicator();
                });
            }, 500);
        }

        // Fungsi untuk simpan gambar gelap
        function simpanGambarGelap() {
            // Tampilkan indikator loading
            showLoadingIndicator("Membuat gambar gelap...");

            // Siapkan konten struk dalam mode gelap
            const container = document.createElement('div');
            container.style.width = '500px';
            container.style.padding = '20px';
            container.style.backgroundColor = '#1f2937'; // gray-800 dark mode
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.top = '-9999px';

            // Clone struk dan terapkan mode gelap
            const strukContent = document.getElementById('printable-area').cloneNode(true);
            strukContent.classList.add('dark');

            // Ubah semua elemen menjadi dark mode
            const allElements = strukContent.querySelectorAll('*');
            allElements.forEach(el => {
                // Background
                if (el.classList.contains('bg-white') || el.classList.contains('bg-gray-50') || el.classList.contains('bg-gray-100')) {
                    el.style.backgroundColor = '#1f2937'; // gray-800
                }
                if (el.classList.contains('dark:bg-gray-800')) {
                    el.style.backgroundColor = '#1f2937'; // gray-800
                }
                if (el.classList.contains('dark:bg-gray-700')) {
                    el.style.backgroundColor = '#374151'; // gray-700
                }
                if (el.classList.contains('dark:bg-blue-900')) {
                    el.style.backgroundColor = '#1e3a8a'; // blue-900
                }
                if (el.classList.contains('dark:bg-green-900')) {
                    el.style.backgroundColor = '#14532d'; // green-900
                }
                if (el.classList.contains('dark:bg-blue-800')) {
                    el.style.backgroundColor = '#1e40af'; // blue-800
                }

                // Text
                if (el.classList.contains('text-gray-700') || el.classList.contains('text-gray-800') || el.classList.contains('text-gray-900')) {
                    el.style.color = '#e5e7eb'; // gray-200
                }
                if (el.classList.contains('dark:text-gray-300')) {
                    el.style.color = '#d1d5db'; // gray-300
                }
                if (el.classList.contains('dark:text-gray-400')) {
                    el.style.color = '#9ca3af'; // gray-400
                }
                if (el.classList.contains('dark:text-gray-200')) {
                    el.style.color = '#e5e7eb'; // gray-200
                }
                if (el.classList.contains('dark:text-blue-400')) {
                    el.style.color = '#60a5fa'; // blue-400
                }
                if (el.classList.contains('dark:text-green-400')) {
                    el.style.color = '#4ade80'; // green-400
                }
                if (el.classList.contains('text-white')) {
                    el.style.color = '#ffffff'; // white
                }

                // Border
                if (el.classList.contains('dark:border-gray-700')) {
                    el.style.borderColor = '#374151'; // gray-700
                }
                if (el.classList.contains('dark:border-blue-800')) {
                    el.style.borderColor = '#1e40af'; // blue-800
                }
                if (el.classList.contains('border-white')) {
                    el.style.borderColor = '#ffffff'; // white
                }

                // Pastikan border dashed tetap dashed
                if (el.classList.contains('border-dashed')) {
                    el.style.borderStyle = 'dashed';
                }

                // SVG handling
                if (el.tagName === 'svg') {
                    el.style.display = 'inline-block';
                    el.setAttribute('fill', 'none');

                    // Atur warna stroke berdasarkan parent atau class
                    if (el.classList.contains('dark:text-blue-400') || el.parentElement?.classList.contains('dark:text-blue-400')) {
                        el.setAttribute('stroke', '#60a5fa'); // blue-400
                    } else if (el.classList.contains('dark:text-green-400') || el.parentElement?.classList.contains('dark:text-green-400')) {
                        el.setAttribute('stroke', '#4ade80'); // green-400
                    } else if (el.classList.contains('text-white') || el.parentElement?.classList.contains('text-white')) {
                        el.setAttribute('stroke', '#ffffff'); // white
                    } else {
                        el.setAttribute('stroke', '#d1d5db'); // gray-300 default
                    }
                }
            });

            // Perbaiki khusus untuk efek gradient dan elemen tertentu
            const gradientEl = strukContent.querySelector('.bg-gradient-to-r');
            if (gradientEl) {
                gradientEl.style.background = 'linear-gradient(to right, #3b82f6, #4f46e5)';
                gradientEl.style.color = '#ffffff';
                const gradientChilds = gradientEl.querySelectorAll('*');
                gradientChilds.forEach(child => {
                    child.style.color = '#ffffff';
                    if (child.tagName === 'svg') {
                        child.setAttribute('stroke', '#ffffff');
                    }
                });
            }

            container.appendChild(strukContent);
            document.body.appendChild(container);

            // Tunggu sebentar agar DOM dirender
            setTimeout(() => {
                // Gunakan html2canvas untuk mengambil gambar
                html2canvas(container, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#1f2937', // gray-800 dark mode
                    onclone: function(clonedDoc) {
                        const clonedElement = clonedDoc.querySelector('#printable-area');
                        if (clonedElement) {
                            // Pastikan semua SVG terlihat
                            const svgs = clonedElement.querySelectorAll('svg');
                            svgs.forEach(svg => {
                                svg.style.display = 'inline-block';

                                // Atur warna stroke berdasarkan parent
                                const parent = svg.parentElement;
                                if (parent.classList.contains('dark:text-blue-400') || parent.style.color === '#60a5fa') {
                                    svg.setAttribute('stroke', '#60a5fa'); // blue-400
                                } else if (parent.classList.contains('dark:text-green-400') || parent.style.color === '#4ade80') {
                                    svg.setAttribute('stroke', '#4ade80'); // green-400
                                } else if (parent.classList.contains('text-white') || parent.style.color === '#ffffff') {
                                    svg.setAttribute('stroke', '#ffffff'); // white
                                } else {
                                    svg.setAttribute('stroke', '#d1d5db'); // gray-300 default
                                }
                            });

                            // Perbaiki warna elemen tertentu
                            const transaksiElementSuccess = clonedElement.querySelector('.dark\\:text-green-400, .text-green-500');
                            if (transaksiElementSuccess) {
                                transaksiElementSuccess.style.color = '#4ade80'; // green-400 dark mode
                                const svgInSuccess = transaksiElementSuccess.querySelector('svg');
                                if (svgInSuccess) {
                                    svgInSuccess.setAttribute('stroke', '#4ade80');
                                }
                            }
                        }
                    }
                }).then(canvas => {
                    try {
                        // Konversi canvas ke URL data dengan format PNG untuk kualitas terbaik
                        const imgData = canvas.toDataURL('image/png');

                        // Buat link download
                        const link = document.createElement('a');
                        link.href = imgData;
                        link.download = 'struk-gannstore-gelap-' + new Date().getTime() + '.png';
                        link.click();

                        // Bersihkan
                        document.body.removeChild(container);
                        hideLoadingIndicator();
                    } catch (e) {
                        console.error('Terjadi kesalahan saat ekspor gambar gelap:', e);
                        alert('Terjadi kesalahan saat ekspor gambar. Silakan coba lagi.');
                        document.body.removeChild(container);
                        hideLoadingIndicator();
                    }
                }).catch(err => {
                    console.error('Terjadi kesalahan pada html2canvas:', err);
                    alert('Terjadi kesalahan saat mengambil gambar. Silakan coba lagi.');
                    document.body.removeChild(container);
                    hideLoadingIndicator();
                });
            }, 500);
        }

        // Fungsi untuk menampilkan loading indicator
        function showLoadingIndicator(message) {
            const loadingIndicator = document.createElement('div');
            loadingIndicator.id = 'loading-indicator';
            loadingIndicator.style.position = 'fixed';
            loadingIndicator.style.top = '0';
            loadingIndicator.style.left = '0';
            loadingIndicator.style.width = '100%';
            loadingIndicator.style.height = '100%';
            loadingIndicator.style.backgroundColor = 'rgba(0,0,0,0.5)';
            loadingIndicator.style.display = 'flex';
            loadingIndicator.style.justifyContent = 'center';
            loadingIndicator.style.alignItems = 'center';
            loadingIndicator.style.zIndex = '9999';
            loadingIndicator.innerHTML = `<div style="background-color: white; padding: 20px; border-radius: 10px;"><p style="color: #333; font-weight: bold;">${message}</p></div>`;
            document.body.appendChild(loadingIndicator);
        }

        // Fungsi untuk menghilangkan loading indicator
        function hideLoadingIndicator() {
            const loadingIndicator = document.getElementById('loading-indicator');
            if (loadingIndicator) {
                document.body.removeChild(loadingIndicator);
            }
        }
    </script>
    @endpush
</x-app-layout>

