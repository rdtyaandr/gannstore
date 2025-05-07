<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
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
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

        <!-- Tombol Upload dan Manual -->
        <div class="flex space-x-4 mb-6">
                <button type="button" onclick="openModal('upload')" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
                Scan Gambar Otomatis
            </button>
                <button type="button" onclick="openModal('manual')" class="bg-green-600 text-white px-6 py-3 rounded-lg shadow hover:bg-green-700 transition">
                Buat Data Manual
            </button>
        </div>

        <!-- Garis Pemisah -->
        <hr class="border-gray-300 mb-6">

        <!-- Riwayat Struk -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Struk</h2>
            @if ($struks->isEmpty())
                <p class="text-gray-600">Belum ada struk yang diunggah.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Produk</th>
                                <th class="p-3 text-left">Tanggal</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($struks as $index => $struk)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $loop->iteration }}</td>
                                    <td class="p-3">{{ $struk->getValue('produk') }}</td>
                                    <td class="p-3">{{ $struk->getValue('tanggal') }}</td>
                                    <td class="p-3 flex space-x-2">
                                        <!-- Tombol Info/Cetak Struk -->
                                        <a href="{{ route('struks.show', $struk) }}" class="bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>

                                        <!-- Tombol Uang Dollar -->
                                        <a href="#" onclick="showFinancialDetails('{{ $struk->id }}')" class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>

                                        <!-- Tombol Edit yang sudah ada -->
                                        <a href="{{ route('struks.edit', $struk) }}" class="bg-yellow-500 text-white p-2 rounded-full hover:bg-yellow-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15.414a2 2 0 01-2.828 0l-4.243-4.243a2 2 0 010-2.828 2 2 0 012.828 0l4.243 4.243"></path>
                                                </svg>
                                        </a>
                                        <form action="{{ route('struks.destroy', $struk) }}" method="POST" onsubmit="return confirm('Hapus struk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $struks->links() }}
            @endif
            </div>
        </div>
    </div>

    <!-- Modal Upload Gambar -->
    <div id="uploadModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('upload')"></div>
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Scan Gambar Otomatis</h2>
                <button type="button" onclick="closeModal('upload')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('struks.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="screenshot" class="block text-gray-700 text-sm font-bold mb-2">Pilih Gambar Struk</label>
                    <input type="file"
                           name="screenshot"
                           id="screenshot"
                           accept="image/*"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100"
                           required>
                    @error('screenshot')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button"
                            onclick="closeModal('upload')"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Scan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Buat Data Manual -->
    <div id="manualModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('manual')"></div>
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Tambah Data Baru</h2>
                <button type="button" onclick="closeModal('manual')" class="text-gray-500 hover:text-gray-700">
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

                <button id="addManualFieldBtn" type="button" class="mt-4 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Field
                </button>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal('manual')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Keuangan -->
    <div id="financialModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('financial')"></div>
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Detail Keuangan</h2>
                <button type="button" onclick="closeModal('financial')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="financialContent" class="space-y-4">
                <!-- Detail keuangan akan diisi di sini -->
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6 mb-4"></div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeModal('financial')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Tutup</button>
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
        });

        const fields = @json($fields);

        function showFinancialDetails(strukId) {
            // Tampilkan modal
            const modal = document.getElementById('financialModal');
            modal.classList.remove('hidden');
            modal.classList.add('show');

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
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-lg mb-2">Informasi Harga</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="text-gray-600">Produk:</div>
                                <div class="font-medium">${data.produk || '-'}</div>

                                <div class="text-gray-600">Harga:</div>
                                <div class="font-medium text-green-600">Rp ${formatRupiah(data.harga || '0')}</div>

                                <div class="text-gray-600">Tanggal:</div>
                                <div class="font-medium">${data.tanggal || '-'}</div>
                            </div>
                        </div>`;

                    if (data.status) {
                        html += `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-lg mb-2">Status Transaksi</h3>
                            <div class="py-2 px-4 ${data.status === 'SUKSES' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'} rounded-lg text-center font-medium">
                                ${data.status}
                            </div>
                        </div>`;
                    }

                    content.innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('financialContent').innerHTML = `
                        <div class="bg-red-100 text-red-700 p-4 rounded-lg">
                            <p>Error: ${error.message}</p>
                        </div>
                    `;
                });
        }

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function openModal(type) {
            const modal = document.getElementById(type + 'Modal');
            modal.classList.remove('hidden');
            modal.classList.add('show');

            if (type === 'upload') {
                renderFields('uploadFields');
            } else if (type === 'manual') {
                renderManualFields();
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

            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200 group';
            fieldDiv.innerHTML = `
                <div class="flex items-start justify-between">
                    <div class="flex-grow">
                        <div class="mb-2">
                            <div class="field-label-display text-sm font-medium text-gray-700 cursor-pointer hover:text-blue-600" onclick="editFieldLabel(this)">
                                ${label || 'Klik untuk menambah nama field'}${isRequired ? '<span class="text-red-500"> *</span>' : ''}
                            </div>
                            <input type="text"
                                placeholder="Nama Field"
                                value="${label}"
                                class="field-label hidden block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                onblur="updateFieldLabel(this)"
                                onkeypress="handleFieldLabelKeyPress(event, this)"
                                data-required="${isRequired}">
                        </div>
                        <div>
                            <input type="text"
                                placeholder="Value"
                                value="${value}"
                                class="field-value block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                ${isRequired ? 'required' : ''}>
                        </div>
                    </div>
                    <button type="button"
                        class="delete-field ml-2 text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                        onclick="deleteField(this)">
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
                        label.className = 'block text-gray-700 text-sm font-bold mb-2';
                        label.textContent = field.label;

                        const input = document.createElement('input');
                        input.type = field.type;
                        input.name = `fields[${field.name}]`;
                        input.className = 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline';
                        input.required = field.is_required;

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

            // Add asterisk if required
            if (isRequired) {
                let asterisk = document.createElement('span');
                asterisk.className = 'text-red-500';
                asterisk.textContent = ' *';
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

            // Jangan izinkan penghapusan field wajib
            if (isRequired) {
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

        // Tambahkan handler untuk form manual submit
        document.getElementById('manualForm').addEventListener('submit', function(e) {
            // Cegah form submission normal
            e.preventDefault();

            // Periksa field wajib
            let requiredFields = document.querySelectorAll('.field-label[data-required="true"]');
            let isValid = true;

            requiredFields.forEach(function(field) {
                const group = field.closest('.group');
                const valueInput = group.querySelector('.field-value');

                if (!valueInput.value.trim()) {
                    isValid = false;
                    valueInput.classList.add('border-red-500');
                } else {
                    valueInput.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Mohon isi semua field wajib!');
                return;
            }

            // Mencoba pendekatan alternatif: buat form baru dan submit secara normal
            // Ini akan memanfaatkan redirect pada controller
            const submitForm = document.createElement('form');
            submitForm.method = 'POST';
            submitForm.action = '{{ route('struks.store') }}';
            submitForm.style.display = 'none';

            // Tambahkan CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            submitForm.appendChild(csrfInput);

            // Tambahkan semua field data
            document.querySelectorAll('#manualFields .field-value').forEach(function(input) {
                if (input.name && input.name.startsWith('data[')) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    submitForm.appendChild(hiddenInput);
                }
            });

            // Tambahkan form ke body dan submit
            document.body.appendChild(submitForm);
            submitForm.submit();
        });

        // Tutup modal ketika menekan tombol Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('upload');
                closeModal('manual');
            }
        });

        // Mengatasi masalah modal backdrop dengan menggunakan jQuery append to body
        $(document).on('show.bs.modal', '.modal', function () {
            $(this).appendTo('body');
        });
    </script>
    @endpush
</x-app-layout>



