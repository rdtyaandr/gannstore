<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <!-- Tambahkan CSS untuk modal -->
    <style>
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .modal-content {
            position: relative;
            z-index: 50;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 45;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

        <!-- Tombol Upload dan Manual -->
        <div class="flex space-x-4 mb-6">
                <button type="button" onclick="openModal('upload')" class="bg-blue-600 dark:bg-blue-700 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                Scan Gambar Otomatis
            </button>
                <button type="button" onclick="openModal('manual')" class="bg-green-600 dark:bg-green-700 text-white px-6 py-3 rounded-lg shadow hover:bg-green-700 dark:hover:bg-green-800 transition">
                Buat Data Manual
            </button>
        </div>

        <!-- Garis Pemisah -->
        <hr class="border-gray-300 dark:border-gray-700 mb-6">

        <!-- Riwayat Struk -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Riwayat Struk</h2>
            @if ($paginatedResults->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">Belum ada struk yang diunggah.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Produk</th>
                                <th class="p-3 text-left">Tanggal</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paginatedResults as $index => $struk)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="p-3 dark:text-gray-300">{{ $loop->iteration }}</td>
                                    <td class="p-3 dark:text-gray-300">{{ $struk->getValue('produk') }}</td>
                                    <td class="p-3 dark:text-gray-300">{{ $struk->getValue('tanggal') }}</td>
                                    <td class="p-3 flex space-x-2">
                                        <!-- Tombol Info/Cetak Struk -->
                                        <a href="{{ route('struks.show', $struk) }}" class="bg-blue-500 dark:bg-blue-600 text-white p-2 rounded-full hover:bg-blue-600 dark:hover:bg-blue-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>

                                        <!-- Tombol Uang Dollar -->
                                        <a href="#" onclick="showFinancialDetails('{{ $struk->id }}')" class="relative bg-green-500 dark:bg-green-600 text-white p-2 rounded-full hover:bg-green-600 dark:hover:bg-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @if(in_array($struk->id, $strukIdsWithCuan ?? []))
                                                <span class="absolute -top-1 -right-1 bg-yellow-400 dark:bg-yellow-500 text-xs text-white rounded-full h-4 w-4 flex items-center justify-center" title="Sudah ada data cuan">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </a>

                                        <!-- Tombol Edit yang sudah ada -->
                                        <a href="{{ route('struks.edit', $struk) }}" class="bg-yellow-500 dark:bg-yellow-600 text-white p-2 rounded-full hover:bg-yellow-600 dark:hover:bg-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $struk->id }}', '{{ $struk->getValue('produk') }}', '{{ $struk->getValue('tanggal') }}')" class="bg-red-500 dark:bg-red-600 text-white p-2 rounded-full hover:bg-red-600 dark:hover:bg-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $paginatedResults->links() }}
            @endif
            </div>
        </div>
    </div>

    <!-- Modal Upload Gambar -->
    <div id="uploadModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('upload')"></div>
        <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold dark:text-gray-200">Scan Gambar Otomatis</h2>
                <button type="button" onclick="closeModal('upload')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('struks.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="screenshot" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Pilih Gambar Struk</label>
                    <input type="file"
                           name="screenshot"
                           id="screenshot"
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  dark:file:bg-blue-900 dark:file:text-blue-200
                                  hover:file:bg-blue-100 dark:hover:file:bg-blue-800"
                           required>
                    @error('screenshot')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button"
                            onclick="closeModal('upload')"
                            class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800">
                        Scan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Buat Data Manual -->
    <div id="manualModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('manual')"></div>
        <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold dark:text-gray-200">Tambah Data Baru</h2>
                <button type="button" onclick="closeModal('manual')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('struks.store') }}" method="POST" id="manualForm">
                @csrf
                <div id="manualFields" class="space-y-4">
                    <!-- Fields will be added here dynamically -->
                </div>

                <button id="addManualFieldBtn" type="button" class="mt-4 w-full bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Field
                </button>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal('manual')" class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-green-600 dark:bg-green-700 text-white px-4 py-2 rounded hover:bg-green-700 dark:hover:bg-green-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Keuangan -->
    <div id="financialModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('financial')"></div>
        <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold dark:text-gray-200">Detail Keuangan</h2>
                <button type="button" onclick="closeModal('financial')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="financialContent" class="space-y-4">
                <!-- Detail keuangan akan diisi di sini -->
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-4"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-4"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-5/6 mb-4"></div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" id="hitungCuanBtn" class="bg-yellow-500 dark:bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-600 dark:hover:bg-yellow-700 mr-2">Hitung Cuan</button>
                <button type="button" id="editCuanBtn" class="bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 mr-2 hidden">Edit Keuntungan</button>
                <button type="button" id="simpanCuanBtn" class="bg-green-600 dark:bg-green-700 text-white px-4 py-2 rounded hover:bg-green-700 dark:hover:bg-green-800 mr-2 hidden">Simpan Keuntungan</button>
                <button type="button" onclick="closeModal('financial')" class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Struk -->
    <div id="deleteModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('delete')"></div>
        <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold dark:text-gray-200">Konfirmasi Hapus Struk</h2>
                <button type="button" onclick="closeModal('delete')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-700 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-300">
                                Apakah Anda yakin ingin menghapus struk ini? Tindakan ini tidak dapat dibatalkan dan semua data terkait juga akan dihapus.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div id="deleteItemDetails" class="text-sm text-gray-700 dark:text-gray-300">
                        <!-- Detail struk yang akan dihapus akan diisi secara dinamis -->
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal('delete')" class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 dark:bg-red-700 text-white px-4 py-2 rounded hover:bg-red-700 dark:hover:bg-red-800">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan semua modal berada di bawah body untuk menghindari masalah z-index
            document.querySelectorAll('.modal').forEach(function(modal) {
                document.body.appendChild(modal);
            });

            // Event listener untuk tombol tambah field di modal manual
            document.getElementById('addManualFieldBtn').addEventListener('click', function() {
                addManualField();
            });

            document.getElementById('createManualButton').addEventListener('click', function() {
                // Buka modal
                openModal('manual');

                // Hapus semua field
                const manualFieldsContainer = document.getElementById('manualFields');
                manualFieldsContainer.innerHTML = '';

                // Tambahkan field wajib
                addManualField('Tanggal', '', true);
                addManualField('Produk', '', true);
                addManualField('Harga', '', true);
            });

            // Inisialisasi format harga
            document.getElementById('manualModal').addEventListener('shown.bs.modal', function () {
                initPriceFormatting();
            });
        });

        const fields = @json($fields);

        // Fungsi untuk menampilkan modal konfirmasi hapus
        function confirmDelete(strukId, produk, tanggal) {
            // Set detail struk yang akan dihapus
            document.getElementById('deleteItemDetails').innerHTML = `
                <p><strong>Produk:</strong> ${produk || 'Tidak ada produk'}</p>
                <p><strong>Tanggal:</strong> ${tanggal || 'Tidak ada tanggal'}</p>
            `;

            // Set action form delete
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = '/struks/' + strukId;

            // Tampilkan modal
            openModal('delete');
        }

        function showFinancialDetails(strukId) {
            // Tampilkan modal
            const modal = document.getElementById('financialModal');
            modal.classList.remove('hidden');
            modal.classList.add('show');

            // Periksa apakah struk sudah memiliki data cuan
            fetch(`/cuan/check/${strukId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal memeriksa data cuan');
                    }
                    return response.json();
                })
                .then(checkResult => {
                    // Ambil data keuangan dari server
                    fetch(`/struks/${strukId}/financial`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal mengambil data keuangan');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Contoh tampilan data keuangan
                            const content = document.getElementById('financialContent');

                            // Tampilkan data yang sebenarnya di sini
                            let html = `
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h3 class="font-medium text-lg mb-2 dark:text-gray-200">Informasi Harga</h3>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="text-gray-600 dark:text-gray-400">Produk:</div>
                                        <div class="font-medium dark:text-gray-300">${data.produk || '-'}</div>

                                        <div class="text-gray-600 dark:text-gray-400">Harga Jual (dari struk):</div>
                                        <div class="font-medium text-green-600 dark:text-green-400">Rp ${formatRupiah(data.harga || '0')}</div>

                                        <div class="text-gray-600 dark:text-gray-400">Tanggal:</div>
                                        <div class="font-medium dark:text-gray-300">${data.tanggal || '-'}</div>`;

                            // Jika sudah ada data cuan, tampilkan harga beli yang sudah tersimpan
                            if (checkResult.exists) {
                                const cuanData = checkResult.data;
                                html += `
                                        <div class="text-gray-600 dark:text-gray-400">Harga Beli (modal):</div>
                                        <div class="font-medium dark:text-gray-300">
                                            <div class="flex items-center">
                                                <span id="hargaBeliDisplay">Rp ${formatRupiah(cuanData.harga_beli || '0')}</span>
                                                <button id="toggleEditBtn" class="ml-2 text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div id="editHargaBeliContainer" class="mt-2 hidden">
                                                <input type="text" id="hargaBeli" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                                   placeholder="Masukkan harga beli baru" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                                Struk ini sudah memiliki data keuntungan. Klik tombol edit untuk mengubah harga beli.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div id="currentCuanInfo" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h3 class="font-medium text-lg mb-2 dark:text-gray-200">Keuntungan Tersimpan</h3>
                                    <div class="py-2 px-4 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 rounded-lg text-center font-medium">
                                        Rp ${formatRupiah(cuanData.keuntungan || '0')}
                                    </div>
                                </div>

                                <div id="newCuanResult" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-4 hidden">
                                    <h3 class="font-medium text-lg mb-2 dark:text-gray-200">Keuntungan Baru</h3>
                                    <div class="py-2 px-4 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-lg text-center font-medium" id="newCuanValue">
                                        -
                                    </div>
                                </div>`;
                            } else {
                                // Jika belum ada data cuan, tampilkan input harga beli
                                html += `
                                        <div class="text-gray-600 dark:text-gray-400">Harga Beli (modal):</div>
                                        <div class="font-medium">
                                            <input type="text" id="hargaBeli" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                                   placeholder="Masukkan harga beli (lebih rendah dari harga jual)" value="">
                                        </div>
                                    </div>
                                </div>`;
                            }

                            if (data.status) {
                                html += `
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-4">
                                    <h3 class="font-medium text-lg mb-2 dark:text-gray-200">Status Transaksi</h3>
                                    <div class="py-2 px-4 ${data.status === 'SUKSES' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300'} rounded-lg text-center font-medium">
                                        ${data.status}
                                    </div>
                                </div>`;
                            }

                            // Tambahkan div untuk menampilkan keuntungan (cuan)
                            html += `
                                <div id="cuanResult" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-4 ${checkResult.exists ? 'hidden' : 'hidden'}">
                                    <h3 class="font-medium text-lg mb-2 dark:text-gray-200">Keuntungan (Cuan)</h3>
                                    <div class="py-2 px-4 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-lg text-center font-medium" id="cuanValue">
                                        -
                                    </div>
                                </div>`;

                            content.innerHTML = html;

                            // Jika belum ada data cuan, sediakan interaksi input dan hitung
                            if (!checkResult.exists) {
                                // Simpan harga jual dari struk dalam variabel untuk perhitungan
                                const hargaJual = parseInt(data.harga?.replace(/\D/g, '') || 0);

                                // Tambahkan event listener untuk input harga beli
                                const hargaBeliInput = document.getElementById('hargaBeli');

                                if (hargaBeliInput) {
                                    hargaBeliInput.addEventListener('input', function(e) {
                                        // Format input sebagai angka rupiah
                                        let value = e.target.value.replace(/\D/g, '');
                                        if (value) {
                                            e.target.value = formatRupiah(value);
                                        }

                                        // Validasi: Pastikan harga beli lebih rendah dari harga jual
                                        const hargaBeliValue = parseInt(value || 0);
                                        const hitungCuanBtn = document.getElementById('hitungCuanBtn');

                                        if (hargaBeliValue >= hargaJual) {
                                            // Tampilkan pesan error jika harga beli lebih dari atau sama dengan harga jual
                                            e.target.classList.add('border-red-500');
                                            if (!document.getElementById('hargaBeliError')) {
                                                const errorMsg = document.createElement('p');
                                                errorMsg.id = 'hargaBeliError';
                                                errorMsg.className = 'text-red-500 text-sm mt-1';
                                                errorMsg.innerText = 'Harga beli harus lebih rendah dari harga jual (Rp ' + formatRupiah(hargaJual) + ')';
                                                e.target.parentNode.appendChild(errorMsg);
                                            }
                                        } else {
                                            // Hapus pesan error jika valid
                                            e.target.classList.remove('border-red-500');
                                            const errorMsg = document.getElementById('hargaBeliError');
                                            if (errorMsg) errorMsg.remove();
                                        }
                                    });
                                }

                                // Tambahkan event listener untuk tombol Hitung Cuan
                                const hitungCuanBtn = document.getElementById('hitungCuanBtn');
                                if (hitungCuanBtn) {
                                    hitungCuanBtn.addEventListener('click', function() {
                                        // Ambil nilai harga beli yang diinput
                                        const hargaBeliStr = hargaBeliInput.value.replace(/\D/g, '');
                                        const hargaBeli = parseInt(hargaBeliStr || 0);

                                        // Hitung keuntungan
                                        const keuntungan = hargaJual - hargaBeli;

                                        // Tampilkan hasil perhitungan
                                        const cuanResult = document.getElementById('cuanResult');
                                        const cuanValue = document.getElementById('cuanValue');

                                        if (cuanResult && cuanValue) {
                                            // Format keuntungan sebagai rupiah
                                            const formattedKeuntungan = formatRupiah(keuntungan);

                                            // Tentukan warna berdasarkan hasil keuntungan
                                            let bgColor = 'bg-blue-100';
                                            let textColor = 'text-blue-800';
                                            let darkBgColor = 'dark:bg-blue-900/50';
                                            let darkTextColor = 'dark:text-blue-300';

                                            if (keuntungan > 0) {
                                                bgColor = 'bg-green-100';
                                                textColor = 'text-green-800';
                                                darkBgColor = 'dark:bg-green-900/50';
                                                darkTextColor = 'dark:text-green-300';
                                            } else if (keuntungan < 0) {
                                                bgColor = 'bg-red-100';
                                                textColor = 'text-red-800';
                                                darkBgColor = 'dark:bg-red-900/50';
                                                darkTextColor = 'dark:text-red-300';
                                            }

                                            // Tampilkan div hasil
                                            cuanResult.classList.remove('hidden');

                                            // Update tampilan
                                            cuanValue.className = `py-2 px-4 ${bgColor} ${darkBgColor} ${textColor} ${darkTextColor} rounded-lg text-center font-medium`;
                                            cuanValue.textContent = `Rp ${formattedKeuntungan}`;

                                            // Tampilkan tombol simpan keuntungan
                                            document.getElementById('simpanCuanBtn').classList.remove('hidden');
                                        }
                                    });
                                }

                                // Tambahkan event listener untuk tombol Simpan Keuntungan
                                const simpanCuanBtn = document.getElementById('simpanCuanBtn');
                                if (simpanCuanBtn) {
                                    simpanCuanBtn.addEventListener('click', function() {
                                        // Ambil nilai harga beli yang diinput
                                        const hargaBeliInput = document.getElementById('hargaBeli');
                                        const hargaBeliStr = hargaBeliInput.value.replace(/\D/g, '');
                                        const hargaBeli = parseInt(hargaBeliStr || 0);

                                        // Simpan data keuntungan ke database
                                        fetch('{{ route("cuan.store") }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                struk_id: strukId,
                                                harga_beli: hargaBeli
                                            })
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Gagal menyimpan data keuntungan');
                                            }
                                            return response.json();
                                        })
                                        .then(result => {
                                            if (result.success) {
                                                // Redirect ke halaman cuan
                                                window.location.href = '{{ route("cuan.index") }}';
                                            } else {
                                                throw new Error(result.message || 'Gagal menyimpan data keuntungan');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                        });
                                    });
                                }
                            } else {
                                // Jika sudah ada data cuan, sembunyikan tombol hitung dan tampilkan tombol edit
                                document.getElementById('hitungCuanBtn').classList.add('hidden');
                                document.getElementById('editCuanBtn').classList.remove('hidden');

                                // Simpan data cuan yang sudah ada
                                const cuanData = checkResult.data;

                                // Simpan harga jual dari struk dalam variabel untuk perhitungan
                                const hargaJual = parseInt(data.harga?.replace(/\D/g, '') || 0);
                                const currentHargaBeli = parseInt(cuanData.harga_beli);

                                // Toggle edit mode saat tombol edit diklik
                                const toggleEditBtn = document.getElementById('toggleEditBtn');
                                if (toggleEditBtn) {
                                    toggleEditBtn.addEventListener('click', function() {
                                        const editContainer = document.getElementById('editHargaBeliContainer');
                                        if (editContainer.classList.contains('hidden')) {
                                            // Show edit mode
                                            editContainer.classList.remove('hidden');
                                            document.getElementById('hitungCuanBtn').classList.remove('hidden');
                                            document.getElementById('simpanCuanBtn').classList.add('hidden');
                                            this.innerHTML = `
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            `;

                                            // Isi input dengan nilai harga beli yang sudah ada
                                            const hargaBeliInput = document.getElementById('hargaBeli');
                                            if (hargaBeliInput) {
                                                hargaBeliInput.value = formatRupiah(currentHargaBeli);

                                                hargaBeliInput.addEventListener('input', function(e) {
                                                    // Format input sebagai angka rupiah
                                                    let value = e.target.value.replace(/\D/g, '');
                                                    if (value) {
                                                        e.target.value = formatRupiah(value);
                                                    }

                                                    // Validasi: Pastikan harga beli lebih rendah dari harga jual
                                                    const hargaBeliValue = parseInt(value || 0);

                                                    if (hargaBeliValue >= hargaJual) {
                                                        // Tampilkan pesan error jika harga beli lebih dari atau sama dengan harga jual
                                                        e.target.classList.add('border-red-500');
                                                        if (!document.getElementById('hargaBeliError')) {
                                                            const errorMsg = document.createElement('p');
                                                            errorMsg.id = 'hargaBeliError';
                                                            errorMsg.className = 'text-red-500 text-sm mt-1';
                                                            errorMsg.innerText = 'Harga beli harus lebih rendah dari harga jual (Rp ' + formatRupiah(hargaJual) + ')';
                                                            e.target.parentNode.appendChild(errorMsg);
                                                        }
                                                    } else {
                                                        // Hapus pesan error jika valid
                                                        e.target.classList.remove('border-red-500');
                                                        const errorMsg = document.getElementById('hargaBeliError');
                                                        if (errorMsg) errorMsg.remove();
                                                    }
                                                });
                                            }
                                        } else {
                                            // Hide edit mode
                                            editContainer.classList.add('hidden');
                                            document.getElementById('hitungCuanBtn').classList.add('hidden');
                                            document.getElementById('simpanCuanBtn').classList.add('hidden');
                                            document.getElementById('newCuanResult').classList.add('hidden');
                                            this.innerHTML = `
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            `;
                                        }
                                    });
                                }

                                // Tambahkan event listener untuk tombol Hitung Cuan saat mode edit
                                const hitungCuanBtn = document.getElementById('hitungCuanBtn');
                                if (hitungCuanBtn) {
                                    hitungCuanBtn.addEventListener('click', function() {
                                        // Ambil nilai harga beli yang diinput
                                        const hargaBeliInput = document.getElementById('hargaBeli');
                                        const hargaBeliStr = hargaBeliInput.value.replace(/\D/g, '');
                                        const hargaBeli = parseInt(hargaBeliStr || 0);

                                        // Hitung keuntungan baru
                                        const keuntungan = hargaJual - hargaBeli;

                                        // Tampilkan hasil perhitungan
                                        const newCuanResult = document.getElementById('newCuanResult');
                                        const newCuanValue = document.getElementById('newCuanValue');

                                        if (newCuanResult && newCuanValue) {
                                            // Format keuntungan sebagai rupiah
                                            const formattedKeuntungan = formatRupiah(keuntungan);

                                            // Tentukan warna berdasarkan hasil keuntungan
                                            let bgColor = 'bg-blue-100';
                                            let textColor = 'text-blue-800';
                                            let darkBgColor = 'dark:bg-blue-900/50';
                                            let darkTextColor = 'dark:text-blue-300';

                                            if (keuntungan > 0) {
                                                bgColor = 'bg-green-100';
                                                textColor = 'text-green-800';
                                                darkBgColor = 'dark:bg-green-900/50';
                                                darkTextColor = 'dark:text-green-300';
                                            } else if (keuntungan < 0) {
                                                bgColor = 'bg-red-100';
                                                textColor = 'text-red-800';
                                                darkBgColor = 'dark:bg-red-900/50';
                                                darkTextColor = 'dark:text-red-300';
                                            }

                                            // Tampilkan div hasil
                                            newCuanResult.classList.remove('hidden');

                                            // Update tampilan
                                            newCuanValue.className = `py-2 px-4 ${bgColor} ${darkBgColor} ${textColor} ${darkTextColor} rounded-lg text-center font-medium`;
                                            newCuanValue.textContent = `Rp ${formattedKeuntungan}`;

                                            // Tampilkan tombol simpan keuntungan
                                            document.getElementById('simpanCuanBtn').classList.remove('hidden');
                                        }
                                    });
                                }

                                // Tambahkan event listener untuk tombol Simpan Keuntungan untuk update
                                const simpanCuanBtn = document.getElementById('simpanCuanBtn');
                                if (simpanCuanBtn) {
                                    simpanCuanBtn.addEventListener('click', function() {
                                        // Ambil nilai harga beli yang diinput
                                        const hargaBeliInput = document.getElementById('hargaBeli');
                                        const hargaBeliStr = hargaBeliInput.value.replace(/\D/g, '');
                                        const hargaBeli = parseInt(hargaBeliStr || 0);

                                        // Update data keuntungan di database
                                        fetch('{{ route("cuan.update") }}', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                struk_id: strukId,
                                                harga_beli: hargaBeli
                                            })
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Gagal memperbarui data keuntungan');
                                            }
                                            return response.json();
                                        })
                                        .then(result => {
                                            if (result.success) {
                                                // Update tampilan data yang sudah tersimpan
                                                document.getElementById('hargaBeliDisplay').textContent = `Rp ${formatRupiah(hargaBeli)}`;

                                                // Update keuntungan yang ditampilkan
                                                const updatedKeuntungan = result.data.keuntungan;
                                                document.querySelector('#currentCuanInfo .py-2').textContent = `Rp ${formatRupiah(updatedKeuntungan)}`;

                                                // Sembunyikan mode edit
                                                document.getElementById('editHargaBeliContainer').classList.add('hidden');
                                                document.getElementById('hitungCuanBtn').classList.add('hidden');
                                                document.getElementById('simpanCuanBtn').classList.add('hidden');
                                                document.getElementById('newCuanResult').classList.add('hidden');

                                                // Reset tombol edit
                                                document.getElementById('toggleEditBtn').innerHTML = `
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                `;

                                                // Tampilkan pesan sukses
                                                alert('Data keuntungan berhasil diperbarui');
                                            } else {
                                                throw new Error(result.message || 'Gagal memperbarui data keuntungan');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error: ' + error.message);
                                        });
                                    });
                                }

                                // Tambahkan event listener untuk tombol Edit Keuntungan
                                const editCuanBtn = document.getElementById('editCuanBtn');
                                if (editCuanBtn) {
                                    editCuanBtn.addEventListener('click', function() {
                                        // Toggle tampilan edit
                                        toggleEditBtn.click();
                                    });
                                }
                            }
                        })
                        .catch(error => {
                            document.getElementById('financialContent').innerHTML = `
                                <div class="bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 p-4 rounded-lg">
                                    <p>Error: ${error.message}</p>
                                </div>
                            `;
                        });
                })
                .catch(error => {
                    console.error('Error checking cuan data:', error);
                });
        }

        // Fungsi untuk memformat angka dengan pemisah ribuan
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk inisialisasi format harga
        function initPriceFormatting() {
            document.querySelectorAll('#manualFields .field-value').forEach(input => {
                const fieldDiv = input.closest('.group');
                if (fieldDiv) {
                    const labelElement = fieldDiv.querySelector('.field-label-display');
                    if (labelElement && labelElement.textContent.trim().toLowerCase().includes('harga')) {
                        setupPriceField(input);
                    }
                }
            });
        }

        // Setup format harga pada elemen input
        function setupPriceField(input) {
            input.setAttribute('inputmode', 'numeric');
            // Hapus attribute pattern agar tidak menampilkan "please match the requested format"
            // input.setAttribute('pattern', '[0-9]*');

            // Format nilai awal jika ada
            if (input.value) {
                let value = input.value.replace(/\D/g, '');
                if (value) {
                    input.value = formatRupiah(value);
                }
            }

            // Event untuk format saat input
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value) {
                    e.target.value = formatRupiah(value);
                }
            });

            // Event untuk cegah input non-angka
            input.addEventListener('keypress', function(e) {
                const charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        }

        function openModal(type) {
            const modal = document.getElementById(type + 'Modal');
            modal.classList.remove('hidden');
            modal.classList.add('show');

            if (type === 'upload') {
                renderFields('uploadFields');
            } else if (type === 'manual') {
                renderManualFields();
                // Panggil fungsi untuk format harga setelah field dirender
                setTimeout(() => {
                    initPriceFormatting();
                }, 200);
            }
        }

        function closeModal(type) {
            const modal = document.getElementById(type + 'Modal');
            modal.classList.remove('show');
            modal.classList.add('hidden');
        }

        function renderManualFields() {
            const container = document.getElementById('manualFields');
            container.innerHTML = '';

            // Ambil field dari database, tapi hanya tampilkan Tanggal, Produk, dan Harga
            fetch('/struk-fields')
                .then(response => response.json())
                .then(fields => {
                    // Ambil field wajib (tanggal, produk, harga)
                    const requiredFieldNames = ['tanggal', 'produk', 'harga'];

                    // Filter field yang wajib ditampilkan dulu
                    const requiredFields = fields.filter(field =>
                        requiredFieldNames.includes(field.name.toLowerCase()) || field.is_required
                    );

                    // Urutkan field wajib agar tampil sesuai urutan
                    requiredFields.sort((a, b) => {
                        const aIndex = requiredFieldNames.indexOf(a.name.toLowerCase());
                        const bIndex = requiredFieldNames.indexOf(b.name.toLowerCase());
                        if (aIndex !== -1 && bIndex !== -1) {
                            return aIndex - bIndex;
                        }
                        if (aIndex !== -1) return -1;
                        if (bIndex !== -1) return 1;
                        return a.order - b.order;
                    });

                    // Tampilkan field wajib
                    requiredFields.forEach(field => {
                        addManualField(field.label, '', field.is_required);
                    });
                });
        }

        function addManualField(label = '', value = '', isRequired = false) {
            const container = document.getElementById('manualFields');
            const fieldId = 'field_' + Date.now();
            const isWajibField = ['tanggal', 'produk', 'harga'].includes(label.toLowerCase());

            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 group';
            fieldDiv.innerHTML = `
                <div class="flex items-start justify-between">
                    <div class="flex-grow">
                        <div class="mb-2">
                            <div class="field-label-display text-sm font-medium text-gray-700 dark:text-gray-300 ${isWajibField || isRequired ? '' : 'cursor-pointer hover:text-blue-600 dark:hover:text-blue-400'}" ${isWajibField || isRequired ? '' : 'onclick="editFieldLabel(this)"'}>
                                ${label || 'Klik untuk menambah nama field'} ${isWajibField || isRequired ? '<span class="text-red-500">*</span>' : ''}
                            </div>
                            <input type="text"
                                placeholder="Nama Field"
                                value="${label}"
                                class="field-label hidden block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                onblur="updateFieldLabel(this)"
                                onkeypress="handleFieldLabelKeyPress(event, this)"
                                ${isWajibField || isRequired ? 'readonly' : ''}
                                data-required="${isRequired || isWajibField}">
                        </div>
                        <div>
                            <input type="text"
                                placeholder="Value"
                                value="${value}"
                                class="field-value block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                ${isRequired || isWajibField ? 'required' : ''}>
                        </div>
                    </div>
                    <button type="button"
                        class="delete-field ml-2 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity"
                        onclick="deleteField(this)"
                        ${isWajibField || isRequired ? 'disabled style="display: none;"' : ''}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(fieldDiv);

            // Dapatkan elemen input nilai
            const valueInput = fieldDiv.querySelector('.field-value');

            // Tambahkan atribut name
            valueInput.name = 'data[' + (label || 'temp_' + fieldId) + ']';

            // Tambahkan event listener untuk format angka pada field harga
            if (label && label.toLowerCase().includes('harga')) {
                valueInput.setAttribute('inputmode', 'numeric');
                // Hapus pattern untuk menghindari validasi format yang menyebabkan error
                // valueInput.setAttribute('pattern', '[0-9]*');

                // Event untuk hanya membolehkan input angka dan memformat dengan pemisah ribuan
                valueInput.addEventListener('input', function(e) {
                    // Hapus karakter non-angka
                    let value = e.target.value.replace(/\D/g, '');

                    // Format dengan pemisah ribuan
                    if (value) {
                        e.target.value = formatRupiah(value);
                    }
                });

                // Event untuk mencegah input karakter non-angka
                valueInput.addEventListener('keypress', function(e) {
                    const charCode = (e.which) ? e.which : e.keyCode;
                    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                        e.preventDefault();
                        return false;
                    }
                    return true;
                });
            }

            return fieldDiv;
        }

        function renderFields(containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            // Ambil field dari database
            fetch('/struk-fields')
                .then(response => response.json())
                .then(fields => {
                    fields.forEach(field => {
                        const fieldDiv = document.createElement('div');
                        fieldDiv.className = 'mb-4';

                        const label = document.createElement('label');
                        label.className = 'block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2';
                        label.textContent = field.label;

                        const input = document.createElement('input');
                        input.type = field.type;
                        input.name = `fields[${field.name}]`;
                        input.className = 'shadow appearance-none border dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline';
                        input.required = field.is_required;

                        // Tambahkan atribut khusus untuk field harga
                        if (field.name.toLowerCase().includes('harga')) {
                            input.setAttribute('inputmode', 'numeric');
                            input.setAttribute('pattern', '[0-9]*');

                            // Event untuk hanya membolehkan input angka dan memformat dengan pemisah ribuan
                            input.addEventListener('input', function(e) {
                                // Hapus karakter non-angka
                                let value = e.target.value.replace(/\D/g, '');

                                // Format dengan pemisah ribuan
                                if (value) {
                                    e.target.value = formatRupiah(value);
                                }
                            });

                            // Cegah input karakter non-angka
                            input.addEventListener('keypress', function(e) {
                                const charCode = (e.which) ? e.which : e.keyCode;
                                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                                    e.preventDefault();
                                    return false;
                                }
                                return true;
                            });
                        }

                        fieldDiv.appendChild(label);
                        fieldDiv.appendChild(input);
                        container.appendChild(fieldDiv);
                    });
                });
        }

        function editFieldLabel(element) {
            const container = element.parentElement;
            const input = container.querySelector('.field-label');
            const display = container.querySelector('.field-label-display');

            display.classList.add('hidden');
            input.classList.remove('hidden');
            input.focus();
        }

        function updateFieldLabel(input) {
            const container = input.parentElement;
            const display = container.querySelector('.field-label-display');
            const value = input.value.trim();
            const isRequired = input.getAttribute('data-required') === 'true';

            // Update display text
            display.textContent = value || 'Klik untuk menambah nama field';

            // Tambahkan tanda bintang untuk field wajib
            if (isRequired) {
                const asterisk = document.createElement('span');
                asterisk.className = 'text-red-500';
                asterisk.textContent = '*';
                display.appendChild(asterisk);
            }

            display.classList.remove('hidden');
            input.classList.add('hidden');

            // Update name attribute of the value input
            const fieldDiv = input.closest('.group');
            const valueInput = fieldDiv.querySelector('.field-value');
            if (valueInput) {
                valueInput.name = 'data[' + (value || 'temp_field_' + Date.now()) + ']';
            }
        }

        function handleFieldLabelKeyPress(event, input) {
            if (event.key === 'Enter') {
                event.preventDefault();
                input.blur();
            }
        }

        function deleteField(button) {
            const fieldDiv = button.closest('.group');
            const labelDisplay = fieldDiv.querySelector('.field-label-display');
            const isRequired = fieldDiv.querySelector('.field-label').getAttribute('data-required') === 'true';
            const labelText = labelDisplay.textContent.trim().toLowerCase();
            const isWajibField = ['tanggal', 'produk', 'harga'].includes(labelText.replace(/\s+\*$/, '').toLowerCase());

            // Jangan izinkan penghapusan field wajib dan field tanggal, produk, harga
            if (isRequired || isWajibField) {
                alert('Field wajib tidak dapat dihapus!');
                return;
            }

            fieldDiv.remove();
        }

        // Fungsi untuk menangani upload gambar
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/struks', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Isi field-field dengan data OCR
                    Object.keys(data.ocr_data).forEach(fieldName => {
                        const input = document.querySelector(`input[name="fields[${fieldName}]"]`);
                        if (input) {
                            input.value = data.ocr_data[fieldName];
                        }
                    });
                }
            });
        });

        // Validasi form manual sebelum submit
        document.getElementById('manualForm').addEventListener('submit', function(e) {
            // Validasi field wajib
            const wajibFields = ['Tanggal', 'Produk', 'Harga'];
            const missingFields = [];
            let fieldExists = {};
            let duplicateFields = [];

            // Cek field yang ada
            document.querySelectorAll('#manualFields .field-label-display').forEach(label => {
                const labelText = label.textContent.trim().replace(/\s+\*$/, '');
                if (fieldExists[labelText.toLowerCase()]) {
                    duplicateFields.push(labelText);
                }
                fieldExists[labelText.toLowerCase()] = true;
            });

            wajibFields.forEach(wajibField => {
                const fieldElements = [...document.querySelectorAll('#manualFields .field-label-display')];
                const fieldElement = fieldElements.find(el => {
                    return el.textContent.trim().toLowerCase().replace(/\s+\*$/, '') === wajibField.toLowerCase();
                });

                if (fieldElement) {
                    const valueInput = fieldElement.closest('.group').querySelector('.field-value');
                    if (!valueInput.value.trim()) {
                        missingFields.push(wajibField);
                        // Highlight the field
                        valueInput.classList.add('border-red-500');
                    } else {
                        valueInput.classList.remove('border-red-500');
                    }
                } else {
                    missingFields.push(wajibField);
                }
            });

            // Jika ada field wajib yang kosong atau duplikat, tampilkan peringatan dan berhenti
            if (missingFields.length > 0 || duplicateFields.length > 0) {
                e.preventDefault();
                showValidationModal(missingFields, duplicateFields);
                return false;
            }

            return true;
        });

        // Modal peringatan validasi
        function showValidationModal(missingFields, duplicateFields) {
            // Buat modal jika belum ada
            let validationModal = document.getElementById('validationModal');
            if (!validationModal) {
                validationModal = document.createElement('div');
                validationModal.id = 'validationModal';
                validationModal.className = 'modal';

                const modalHTML = `
                    <div class="modal-backdrop" onclick="closeValidationModal()"></div>
                    <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold dark:text-gray-200">Validasi Form</h2>
                            <button type="button" onclick="closeValidationModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mb-4">
                            <div id="validationErrors" class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-400 dark:border-red-600 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p id="errorMessage" class="text-sm text-red-700 dark:text-red-200">
                                            Harap perbaiki kesalahan berikut:
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <ul id="validationList" class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-300">
                                    <!-- Daftar kesalahan akan diisi secara dinamis -->
                                </ul>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button onclick="closeValidationModal()" class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800">
                                Baik, Saya Mengerti
                            </button>
                        </div>
                    </div>
                `;

                validationModal.innerHTML = modalHTML;
                document.body.appendChild(validationModal);
            }

            // Tampilkan modal
            validationModal.style.display = 'flex';

            // Isi pesan kesalahan
            const listContainer = validationModal.querySelector('#validationList');
            listContainer.innerHTML = '';

            // Tampilkan field yang kosong
            if (missingFields.length > 0) {
                const missingHeader = document.createElement('li');
                missingHeader.className = 'font-semibold mb-1';
                missingHeader.textContent = 'Field wajib yang kosong:';
                listContainer.appendChild(missingHeader);

                missingFields.forEach(field => {
                    const li = document.createElement('li');
                    li.className = 'ml-4';
                    li.textContent = field;
                    listContainer.appendChild(li);
                });
            }

            // Tampilkan field yang duplikat
            if (duplicateFields.length > 0) {
                if (missingFields.length > 0) {
                    // Tambahkan pemisah jika ada daftar sebelumnya
                    const separator = document.createElement('li');
                    separator.className = 'h-1 bg-gray-200 dark:bg-gray-600 my-2';
                    separator.innerHTML = '&nbsp;';
                    listContainer.appendChild(separator);
                }

                const duplicateHeader = document.createElement('li');
                duplicateHeader.className = 'font-semibold mb-1';
                duplicateHeader.textContent = 'Field yang duplikat:';
                listContainer.appendChild(duplicateHeader);

                duplicateFields.forEach(field => {
                    const li = document.createElement('li');
                    li.className = 'ml-4';
                    li.textContent = field;
                    listContainer.appendChild(li);
                });
            }
        }

        function closeValidationModal() {
            const validationModal = document.getElementById('validationModal');
            if (validationModal) {
                validationModal.style.display = 'none';
            }
        }
    </script>
    @endpush
</x-app-layout>



