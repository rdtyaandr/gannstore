<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Struk') }}
            </h2>
            <button onclick="printStruk()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Struk
            </button>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Untuk Diprint -->
                    <div id="printable-area" class="mx-auto max-w-lg">
                        <!-- Header Transaksi dengan Efek Gradien -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-8 rounded-lg shadow-md mb-6">
                            <div class="text-center">
                                <h1 class="text-3xl font-bold mb-2">Detail Transaksi</h1>
                                <div class="flex items-center justify-center space-x-2 bg-white/20 rounded-full py-2 px-4 inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $struk->getValue('tanggal') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Utama -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100">
                            <!-- Produk dan Harga - Bagian Paling Penting -->
                            <div class="bg-gray-50 p-6 border-b border-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <span class="font-semibold text-lg text-gray-700">Produk</span>
                                    </div>
                                    <span class="text-lg">{{ $struk->getValue('produk') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-semibold text-lg text-gray-700">Harga</span>
                                    </div>
                                    <span class="text-lg font-bold text-green-600">
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
                                @if($struk->getValue('id_transaksi'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        <span class="text-gray-600">ID Transaksi</span>
                                    </div>
                                    <span class="font-medium">{{ $struk->getValue('id_transaksi') }}</span>
                                </div>
                                @endif

                                @if($struk->getValue('nomor_hp'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-gray-600">Nomor HP</span>
                                    </div>
                                    <span class="font-medium">{{ $struk->getValue('nomor_hp') }}</span>
                                </div>
                                @endif

                                @if($struk->getValue('pembayaran'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="text-gray-600">Pembayaran</span>
                                    </div>
                                    <span class="font-medium">{{ $struk->getValue('pembayaran') }}</span>
                                </div>
                                @endif

                                @if($struk->getValue('status'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-600">Status</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full font-medium text-sm {{ $struk->getValue('status') == 'SUKSES' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $struk->getValue('status') }}
                                    </span>
                                </div>
                                @endif

                                <!-- Tambahkan field lain jika ada -->
                                @foreach($struk->data as $label => $value)
                                    @php
                                        $skipFields = ['produk', 'harga', 'tanggal', 'id_transaksi', 'nomor_hp', 'pembayaran', 'status', 'sn_ref'];
                                    @endphp

                                    @if(!empty($value) && !in_array(strtolower($label), $skipFields))
                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-gray-600">{{ $label }}</span>
                                        </div>
                                        <span class="font-medium">{{ $value }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- SN/Ref dalam Card Terpisah -->
                        @if($struk->getValue('sn_ref'))
                        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100">
                            <div class="p-6">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="font-semibold text-gray-700">SN/Ref</h3>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <p class="text-xs break-all text-gray-600">{{ $struk->getValue('sn_ref') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Footer Tanda Terima -->
                        <div class="text-center mt-8 pt-4">
                            <div class="inline-block border-2 border-dashed border-gray-200 rounded-lg p-4 mb-4">
                                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">Transaksi Berhasil</p>
                            </div>

                            <p class="text-gray-500 text-sm">Terima kasih telah bertransaksi</p>
                            <p class="text-gray-400 text-xs mt-2">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Tombol navigasi non-printable -->
                    <div class="mt-8 flex justify-end print:hidden">
                        <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg shadow-sm hover:bg-gray-300 transition-all duration-200 mr-3">
                            Kembali
                        </a>
                        <a href="{{ route('struks.edit', $struk) }}" class="bg-yellow-500 text-white px-5 py-2 rounded-lg shadow-sm hover:bg-yellow-600 transition-all duration-200">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print function -->
    @push('scripts')
    <script>
        function printStruk() {
            const printContents = document.getElementById('printable-area').innerHTML;
            const originalContents = document.body.innerHTML;

            // Buat style untuk print
            const printStyles = `
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            width: 80mm;
                            margin: 0 auto;
                            padding: 5mm;
                        }
                        * {
                            box-sizing: border-box;
                        }
                        h1 {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            text-align: center;
                        }
                        .bg-gradient-to-r, .bg-white, .rounded-lg, .shadow-md {
                            background: white !important;
                            box-shadow: none !important;
                            border: none !important;
                        }
                        svg {
                            display: none;
                        }
                        .flex {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 8px;
                            border-bottom: 1px dotted #ddd;
                            padding-bottom: 5px;
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
                        .text-green-600, .text-yellow-600 {
                            font-weight: bold;
                        }
                        .text-center {
                            text-align: center;
                        }
                        .mt-8 {
                            margin-top: 15px;
                        }
                        .space-y-4 > * {
                            margin-bottom: 8px;
                        }
                        .bg-green-100, .bg-yellow-100 {
                            padding: 3px;
                            border: 1px solid #ddd;
                            border-radius: 3px;
                        }
                        .rounded-full {
                            border-radius: 3px;
                        }
                        .border-2, .border-dashed {
                            border: 1px dashed #ddd;
                            padding: 5px;
                            margin: 10px 0;
                            text-align: center;
                        }
                        .p-3, .p-4, .p-6, .p-8 {
                            padding: 0;
                            margin: 5px 0;
                        }
                    }
                </style>
            `;

            document.body.innerHTML = printStyles + '<div class="print-container">' + printContents + '</div>';

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
    @endpush
</x-app-layout>
