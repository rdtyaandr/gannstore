<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('Detail Struk') }}
            </h2>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center shadow-md transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Struk
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                    <div class="py-1">
                        <button onclick="printStruk()" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Cetak ke Printer
                            </div>
                        </button>
                        <button onclick="exportToJPG()" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Simpan sebagai JPG
                            </div>
                        </button>
                        <button onclick="captureScreenshot()" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ambil Screenshot
                            </div>
                        </button>
                        <button onclick="exportToPDF()" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Simpan sebagai PDF
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                            <!-- Produk dan Harga - Bagian Paling Penting -->
                            <div class="bg-gradient-to-b from-gray-50 to-white p-6 border-b border-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-lg text-gray-700">Produk</span>
                                    </div>
                                    <span class="text-lg bg-gray-100 py-1 px-3 rounded-lg">{{ $struk->getValue('produk') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-lg text-gray-700">Harga</span>
                                    </div>
                                    <span class="text-lg font-bold bg-green-100 text-green-700 py-1 px-3 rounded-lg">
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
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3 hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600">ID Transaksi</span>
                                    </div>
                                    <span class="font-medium">{{ $struk->getValue('id_transaksi') }}</span>
                                </div>
                                @endif

                                @if($struk->getValue('nomor_hp'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3 hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600">Nomor HP</span>
                                    </div>
                                    <span class="font-medium">{{ $struk->getValue('nomor_hp') }}</span>
                                </div>
                                @endif

                                @if($struk->getValue('pembayaran'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3 hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600">Pembayaran</span>
                                    </div>
                                    <span class="font-medium">{{ $struk->getValue('pembayaran') }}</span>
                                </div>
                                @endif

                                @if($struk->getValue('status'))
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3 hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-1.5 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600">Status</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full font-medium text-sm {{ $struk->getValue('status') == 'SUKSES' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} shadow-sm">
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
                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3 hover:bg-gray-50 p-2 rounded transition-colors">
                                        <div class="flex items-center">
                                            <div class="bg-blue-50 p-1.5 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-600">{{ $label }}</span>
                                        </div>
                                        <span class="font-medium text-right">{{ Str::limit($value, 15, '...') }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Footer Tanda Terima -->
                        <div class="text-center mt-8 pt-4">
                            <div class="inline-block border-2 border-dashed border-blue-200 rounded-lg p-4 mb-4 bg-blue-50/50">
                                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700 font-medium">Transaksi Berhasil</p>
                            </div>

                            <p class="text-gray-600 font-medium">Terima kasih telah bertransaksi di</p>
                            <div class="flex justify-center items-center mt-2 mb-3">
                                <img src="{{ asset('images/logo.png') }}" alt="GannStore Logo" class="h-7 w-auto mr-2 drop-shadow-sm">
                                <p class="text-blue-600 font-bold text-lg">GannStore</p>
                            </div>
                            <p class="text-gray-500 text-sm">Kunjungi kami kembali</p>
                            <p class="text-gray-400 text-xs mt-2">{{ $struk->getValue('tanggal') }}</p>
                        </div>
                    </div>

                    <!-- Tombol navigasi non-printable -->
                    <div class="mt-8 flex justify-end print:hidden">
                        <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-700 px-5 py-2 rounded-lg shadow-sm hover:bg-gray-200 transition-all duration-200 mr-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <a href="{{ route('struks.edit', $struk) }}" class="bg-blue-500 text-white px-5 py-2 rounded-lg shadow-sm hover:bg-blue-600 transition-all duration-200 flex items-center">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Tambahkan CSS khusus untuk ekspor
        function addExportStyles(container) {
            // Buat elemen style
            const styleElement = document.createElement('style');
            styleElement.textContent = `
                .export-container * {
                    font-family: Arial, sans-serif !important;
                    box-sizing: border-box !important;
                }
                .export-container {
                    width: 100%;
                    max-width: 500px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    box-shadow: none !important;
                }
                .export-container .bg-gradient-to-r {
                    background: linear-gradient(to right, #3b82f6, #4f46e5) !important;
                    color: #ffffff !important;
                    padding: 20px !important;
                    text-align: center !important;
                    border-radius: 10px !important;
                }
                .export-container svg {
                    display: inline-block !important;
                    width: 20px !important;
                    height: 20px !important;
                    vertical-align: middle !important;
                }
                .export-container .flex {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    margin-bottom: 8px !important;
                    padding-bottom: 5px !important;
                }
                .export-container .justify-center {
                    justify-content: center !important;
                }
                .export-container .items-center {
                    align-items: center !important;
                }
                .export-container .bg-green-100 {
                    background-color: #d1fae5 !important;
                    color: #047857 !important;
                    padding: 3px 8px !important;
                    border-radius: 6px !important;
                }
                .export-container .bg-blue-50 {
                    background-color: #eff6ff !important;
                    padding: 6px !important;
                    border-radius: 6px !important;
                }
                .export-container .text-blue-500 {
                    color: #3b82f6 !important;
                }
                .export-container .text-green-700 {
                    color: #047857 !important;
                }
                .export-container .border-b {
                    border-bottom: 1px solid #e5e7eb !important;
                }
                .export-container img {
                    max-height: 40px !important;
                }
            `;

            container.appendChild(styleElement);
            container.classList.add('export-container');
        }

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
                        .text-green-600, .text-yellow-600, .text-gray-700 {
                            font-weight: bold;
                        }
                        .text-center {
                            text-align: center;
                        }
                        .mt-8, .mt-2, .mb-3 {
                            margin-top: 15px;
                        }
                        .mr-2, .mr-3 {
                            margin-right: 5px;
                        }
                        .space-y-4 > * {
                            margin-bottom: 8px;
                        }
                        .bg-green-100, .bg-yellow-100 {
                            padding: 3px;
                            border: 1px solid #ddd;
                        }
                    }
                </style>
            `;

            // Ganti isi document untuk print
            document.body.innerHTML = printStyles + printContents;

            window.print();

            // Kembalikan isi document seperti semula
            document.body.innerHTML = originalContents;
        }

        // Fungsi untuk ekspor ke JPG yang diperbaiki
        function exportToJPG() {
            // Tampilkan indikator loading
            const loadingIndicator = document.createElement('div');
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
            loadingIndicator.innerHTML = '<div style="background-color: white; padding: 20px; border-radius: 10px;"><p style="color: #333; font-weight: bold;">Memproses gambar, mohon tunggu...</p></div>';
            document.body.appendChild(loadingIndicator);

            // Buat clone dari area yang akan diambil gambarnya
            const element = document.getElementById('printable-area');
            const clone = element.cloneNode(true);

            // Buat container khusus untuk ekspor
            const container = document.createElement('div');
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.top = '-9999px';
            container.style.width = '500px'; // Tetapkan lebar tetap
            container.style.backgroundColor = '#ffffff';
            container.style.padding = '20px';
            container.style.border = 'none';
            container.style.overflow = 'hidden';
            container.appendChild(clone);
            document.body.appendChild(container);

            // Tambahkan CSS khusus untuk ekspor
            addExportStyles(container);

            // Tunggu beberapa waktu untuk gambar dimuat
            setTimeout(() => {
                // Persiapkan style untuk ekspor
                fixSvgInNode(container);

                // Opsi untuk html2canvas
                const options = {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    imageTimeout: 15000,
                    logging: false,
                    onclone: function(clonedDoc) {
                        const clonedElement = clonedDoc.querySelector('.export-container');
                        if (clonedElement) {
                            // Konversi elemen Flexbox ke table untuk hasil yang lebih konsisten
                            const flexElements = clonedElement.querySelectorAll('.flex:not(.justify-center)');
                            flexElements.forEach(el => {
                                const table = document.createElement('table');
                                table.style.width = '100%';
                                table.style.borderCollapse = 'collapse';
                                const row = table.insertRow();

                                // Kolom label
                                const cell1 = row.insertCell();
                                cell1.style.textAlign = 'left';
                                cell1.style.paddingBottom = '8px';
                                cell1.style.width = '50%';

                                // Kolom nilai
                                const cell2 = row.insertCell();
                                cell2.style.textAlign = 'right';
                                cell2.style.paddingBottom = '8px';
                                cell2.style.width = '50%';

                                // Pindahkan konten dari flex ke tabel
                                const children = Array.from(el.children);
                                if (children.length >= 2) {
                                    cell1.appendChild(children[0].cloneNode(true));
                                    cell2.appendChild(children[1].cloneNode(true));
                                    el.parentNode.replaceChild(table, el);
                                }
                            });
                        }
                    }
                };

                html2canvas(container, options).then(canvas => {
                    try {
                        // Konversi canvas ke URL data
                        const imgData = canvas.toDataURL('image/jpeg', 1.0);

                        // Buat link download
                        const link = document.createElement('a');
                        link.href = imgData;
                        link.download = 'struk-gannstore-' + new Date().getTime() + '.jpg';
                        link.click();

                        // Bersihkan
                        document.body.removeChild(container);
                        document.body.removeChild(loadingIndicator);
                    } catch (e) {
                        console.error('Terjadi kesalahan saat ekspor JPG:', e);
                        alert('Terjadi kesalahan saat ekspor ke JPG. Silakan coba lagi.');
                        document.body.removeChild(container);
                        document.body.removeChild(loadingIndicator);
                    }
                }).catch(err => {
                    console.error('Terjadi kesalahan pada html2canvas:', err);
                    alert('Terjadi kesalahan saat mengambil gambar. Silakan coba lagi.');
                    document.body.removeChild(container);
                    document.body.removeChild(loadingIndicator);
                });
            }, 500);
        }

        // Fungsi untuk ekspor ke PDF yang diperbaiki
        function exportToPDF() {
            // Tampilkan indikator loading
            const loadingIndicator = document.createElement('div');
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
            loadingIndicator.innerHTML = '<div style="background-color: white; padding: 20px; border-radius: 10px;"><p style="color: #333; font-weight: bold;">Memproses PDF, mohon tunggu...</p></div>';
            document.body.appendChild(loadingIndicator);

            // Buat clone dari area yang akan diambil
            const element = document.getElementById('printable-area');
            const clone = element.cloneNode(true);

            // Buat container khusus untuk ekspor
            const container = document.createElement('div');
            container.className = 'pdf-export-container';
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.width = '210mm'; // A4 width
            container.style.backgroundColor = '#ffffff';
            container.style.overflow = 'hidden';
            container.appendChild(clone);
            document.body.appendChild(container);

            // Tambahkan CSS khusus untuk ekspor
            addExportStyles(container);

            // Perbaiki SVG dan gambar
            fixSvgInNode(container);

            // Tunggu beberapa saat untuk gambar dimuat
            setTimeout(() => {
                // Konfigurasi untuk html2pdf
                const opt = {
                    margin: [15, 15, 15, 15],
                    filename: 'struk-gannstore-' + new Date().getTime() + '.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true,
                        imageTimeout: 15000,
                        letterRendering: true,
                        logging: false,
                        onclone: function(clonedDoc) {
                            const clonedElement = clonedDoc.querySelector('.export-container');
                            if (clonedElement) {
                                // Konversi elemen Flexbox ke table untuk hasil yang lebih konsisten
                                const flexElements = clonedElement.querySelectorAll('.flex:not(.justify-center)');
                                flexElements.forEach(el => {
                                    const table = document.createElement('table');
                                    table.style.width = '100%';
                                    table.style.borderCollapse = 'collapse';
                                    const row = table.insertRow();

                                    // Kolom label
                                    const cell1 = row.insertCell();
                                    cell1.style.textAlign = 'left';
                                    cell1.style.paddingBottom = '8px';
                                    cell1.style.width = '50%';

                                    // Kolom nilai
                                    const cell2 = row.insertCell();
                                    cell2.style.textAlign = 'right';
                                    cell2.style.paddingBottom = '8px';
                                    cell2.style.width = '50%';

                                    // Pindahkan konten dari flex ke tabel
                                    const children = Array.from(el.children);
                                    if (children.length >= 2) {
                                        cell1.appendChild(children[0].cloneNode(true));
                                        cell2.appendChild(children[1].cloneNode(true));
                                        el.parentNode.replaceChild(table, el);
                                    }
                                });
                            }
                        }
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: [210, 297], // A4 custom
                        orientation: 'portrait',
                        compress: true
                    },
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                };

                // Generate PDF
                html2pdf()
                    .set(opt)
                    .from(container)
                    .save()
                    .then(() => {
                        // Bersihkan
                        document.body.removeChild(container);
                        document.body.removeChild(loadingIndicator);
                    })
                    .catch(err => {
                        console.error('Terjadi kesalahan saat ekspor PDF:', err);
                        alert('Terjadi kesalahan saat ekspor ke PDF. Silakan coba lagi.');
                        document.body.removeChild(container);
                        document.body.removeChild(loadingIndicator);
                    });
            }, 500);
        }

        // Fungsi pembantu untuk memperbaiki SVG di node
        function fixSvgInNode(node) {
            // Ganti SVG dengan ikon yang kompatibel
            const svgs = node.querySelectorAll('svg');
            svgs.forEach(svg => {
                // Ambil warna dari elemen parent
                let color = '#3b82f6'; // default blue
                if (svg.closest('.bg-blue-50')) {
                    color = '#3b82f6'; // blue-500
                } else if (svg.closest('.bg-green-100')) {
                    color = '#10b981'; // green-600
                } else {
                    color = '#6b7280'; // gray-500
                }

                // Dapatkan dimensi
                const width = '20px';
                const height = '20px';

                // Ikon dasar
                const iconContainer = document.createElement('div');
                iconContainer.style.display = 'inline-block';
                iconContainer.style.width = width;
                iconContainer.style.height = height;
                iconContainer.style.backgroundColor = color;
                iconContainer.style.WebkitMaskSize = 'cover';
                iconContainer.style.maskSize = 'cover';

                // Tentukan jenis ikon berdasarkan parent
                if (svg.closest('.bg-blue-50')) {
                    iconContainer.style.borderRadius = '4px';
                }

                // Ganti SVG dengan div
                svg.parentNode.replaceChild(iconContainer, svg);
            });

            // Perbaikan untuk tampilan gradien
            const gradientElements = node.querySelectorAll('.bg-gradient-to-r');
            gradientElements.forEach(el => {
                el.style.background = 'linear-gradient(to right, #3b82f6, #4f46e5)';
                el.style.color = '#ffffff';
                el.style.padding = '20px';
                el.style.textAlign = 'center';
                el.style.borderRadius = '10px';
            });

            // Pastikan gambar dimuat dengan benar
            const images = node.querySelectorAll('img');
            images.forEach(img => {
                // Jika gambar dari sumber lain, tambahkan crossorigin
                img.setAttribute('crossorigin', 'anonymous');
                // Ganti dengan URL absolut jika perlu
                if (img.src.startsWith('/')) {
                    img.src = window.location.origin + img.src;
                }
            });

            // Perbaiki warna dan tampilan elemen lain
            const textElements = node.querySelectorAll('[class*="text-"]');
            textElements.forEach(el => {
                if (el.classList.contains('text-green-700')) {
                    el.style.color = '#047857';
                } else if (el.classList.contains('text-blue-600')) {
                    el.style.color = '#2563eb';
                }
            });

            // Perbaikan untuk border
            const borderElements = node.querySelectorAll('.border-b');
            borderElements.forEach(el => {
                el.style.borderBottom = '1px solid #e5e7eb';
                el.style.marginBottom = '8px';
                el.style.paddingBottom = '8px';
            });
        }

        // Fungsi untuk mengambil screenshot langsung dari struk yang terlihat
        function captureScreenshot() {
            // Tampilkan indikator loading
            const loadingIndicator = document.createElement('div');
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
            loadingIndicator.innerHTML = '<div style="background-color: white; padding: 20px; border-radius: 10px;"><p style="color: #333; font-weight: bold;">Mengambil screenshot, mohon tunggu...</p></div>';
            document.body.appendChild(loadingIndicator);

            // Cache scroll position
            const scrollPos = window.scrollY;

            // Scroll ke elemen struk agar terlihat penuh
            const printableArea = document.getElementById('printable-area');

            // Hapus sementara tombol-tombol navigasi dari view selama screenshot
            const actionButtons = document.querySelectorAll('.print\\:hidden');
            actionButtons.forEach(btn => {
                btn.style.display = 'none';
            });

            // Scroll ke elemen
            printableArea.scrollIntoView({block: 'start', inline: 'start'});

            // Tunggu sebentar agar scroll selesai dan semua rendering selesai
            setTimeout(() => {
                // Tangkap screenshot
                html2canvas(printableArea, {
                    scale: 2, // Scale tinggi untuk kualitas terbaik
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    removeContainer: false,
                    // Pastikan semua ikon dan gambar dirender dengan benar
                    onclone: function(clonedDoc) {
                        const clonedElement = clonedDoc.getElementById('printable-area');
                        const svgs = clonedElement.querySelectorAll('svg');
                        svgs.forEach(svg => {
                            svg.style.display = 'inline-block';
                        });
                    }
                }).then(canvas => {
                    try {
                        // Konversi canvas ke URL data dengan format PNG untuk kualitas terbaik
                        const imgData = canvas.toDataURL('image/png');

                        // Buat link download
                        const link = document.createElement('a');
                        link.href = imgData;
                        link.download = 'screenshot-struk-' + new Date().getTime() + '.png';
                        link.click();

                        // Kembalikan tombol-tombol navigasi
                        actionButtons.forEach(btn => {
                            btn.style.display = '';
                        });

                        // Restore scroll position
                        window.scrollTo(0, scrollPos);

                        // Bersihkan
                        document.body.removeChild(loadingIndicator);
                    } catch (e) {
                        console.error('Terjadi kesalahan saat mengambil screenshot:', e);
                        alert('Terjadi kesalahan saat mengambil screenshot. Silakan coba lagi.');

                        // Kembalikan tombol-tombol navigasi
                        actionButtons.forEach(btn => {
                            btn.style.display = '';
                        });

                        // Restore scroll position
                        window.scrollTo(0, scrollPos);

                        document.body.removeChild(loadingIndicator);
                    }
                }).catch(err => {
                    console.error('Terjadi kesalahan pada html2canvas:', err);
                    alert('Terjadi kesalahan saat mengambil screenshot. Silakan coba lagi.');

                    // Kembalikan tombol-tombol navigasi
                    actionButtons.forEach(btn => {
                        btn.style.display = '';
                    });

                    // Restore scroll position
                    window.scrollTo(0, scrollPos);

                    document.body.removeChild(loadingIndicator);
                });
            }, 300); // Tunggu lebih lama untuk memastikan rendering selesai
        }
    </script>
    @endpush
</x-app-layout>
