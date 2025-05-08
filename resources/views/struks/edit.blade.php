<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Struk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <form action="{{ route('struks.update', $struk) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md">
                            <div class="font-medium text-red-600 dark:text-red-400">
                                {{ __('Ada kesalahan pada data yang dimasukkan:') }}
                            </div>
                            <ul class="mt-2 text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="mb-6">
                            <!-- Informasi Struk -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Detail Struk</h3>

                                <!-- Field Dinamis Berdasarkan Data Struk -->
                                <div id="formFields" class="space-y-4">
                                    @php
                                        // Dapatkan data dari field values dan gabungkan dengan data JSON
                                        $strukData = $struk->data ?? [];

                                        // Decode semua nilai di strukData untuk menghilangkan karakter URL-encoded
                                        $decodedStrukData = [];
                                        foreach ($strukData as $key => $val) {
                                            $decodedKey = urldecode($key);
                                            $decodedVal = urldecode($val);
                                            $decodedStrukData[$decodedKey] = $decodedVal;
                                        }
                                        $strukData = $decodedStrukData;

                                        // Ambil semua field yang wajib dan tampilkan terlebih dahulu
                                        $requiredFields = $fields->where('is_required', true)->sortBy('order');
                                        $optionalFields = $fields->where('is_required', false)->sortBy('order');
                                    @endphp

                                    <!-- Field Wajib -->
                                    @foreach($requiredFields as $field)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 group">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="mb-2">
                                                        <div class="field-label-display text-sm font-medium text-gray-700 dark:text-gray-300 {{ in_array(strtolower($field->name), ['tanggal', 'produk', 'harga']) ? '' : 'cursor-pointer hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                            {{ $field->label }} <span class="text-red-500">*</span>
                                                        </div>
                                                        <input type="text"
                                                            placeholder="Nama Field"
                                                            value="{{ $field->label }}"
                                                            class="field-label hidden block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                                            {{ in_array(strtolower($field->name), ['tanggal', 'produk', 'harga']) ? 'readonly' : '' }}
                                                            onblur="updateFieldLabel(this)"
                                                            onkeypress="handleFieldLabelKeyPress(event, this)"
                                                            data-required="true">
                                                    </div>
                                                    <div>
                                                        <input type="text"
                                                            placeholder="Value"
                                                            name="data[{{ $field->label }}]"
                                                            value="{{ old('data.' . $field->label, $struk->getValue($field->name) ?? ($strukData[$field->label] ?? '')) }}"
                                                            class="field-value block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Field Opsional -->
                                    <div class="mt-6 mb-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">Informasi Tambahan</h4>
                                    </div>

                                    @php
                                        // Daftar field wajib yang tidak boleh duplikat
                                        $requiredKeywords = ['tanggal', 'produk', 'harga'];

                                        // Fungsi untuk memeriksa apakah sebuah label adalah field wajib
                                        function isRequiredField($label, $requiredKeywords) {
                                            $label = strtolower(trim($label));
                                            foreach ($requiredKeywords as $keyword) {
                                                if (str_contains($label, $keyword)) {
                                                    return true;
                                                }
                                            }
                                            return false;
                                        }
                                    @endphp

                                    <!-- Field Opsional dari database -->
                                    @foreach($optionalFields as $field)
                                        @php
                                            $fieldValue = $struk->getValue($field->name) ?? ($strukData[$field->label] ?? '');
                                            // Skip jika tidak ada nilai atau field ini adalah field wajib
                                            if (empty($fieldValue) || isRequiredField($field->label, $requiredKeywords)) {
                                                continue;
                                            }

                                            // Pastikan fieldValue adalah string dan bersihkan dari karakter newline
                                            $fieldValue = is_string($fieldValue) ? str_replace(["\r\n", "\r", "\n"], " ", $fieldValue) : $fieldValue;
                                        @endphp

                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 group">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="mb-2">
                                                        <div class="field-label-display text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $field->label }}
                                                        </div>
                                                        <input type="text"
                                                            placeholder="Nama Field"
                                                            value="{{ $field->label }}"
                                                            class="field-label hidden block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                                            onblur="updateFieldLabel(this)"
                                                            onkeypress="handleFieldLabelKeyPress(event, this)"
                                                            data-required="false">
                                                    </div>
                                                    <div>
                                                        <input type="text"
                                                            placeholder="Value"
                                                            name="data[{{ $field->label }}]"
                                                            value="{{ old('data.' . $field->label, $fieldValue) }}"
                                                            class="field-value block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                                    </div>
                                                </div>
                                                <button type="button"
                                                    class="delete-field ml-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity"
                                                    onclick="deleteField(this)">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Tambahan Field Kustom yang mungkin tidak ada di database -->
                                    @foreach($strukData as $label => $value)
                                        @php
                                            // Skip jika tidak ada nilai
                                            if (empty($value)) continue;

                                            // Decode URL-encoded label
                                            $decodedLabel = $label;
                                            $iterations = 0;
                                            $maxIterations = 5;

                                            while (strpos($decodedLabel, '%') !== false && $iterations < $maxIterations) {
                                                $newLabel = urldecode($decodedLabel);
                                                if ($newLabel === $decodedLabel) break;
                                                $decodedLabel = $newLabel;
                                                $iterations++;
                                            }

                                            // Bersihkan label
                                            $cleanedLabel = trim(preg_replace('/\s+/', ' ', $decodedLabel));
                                            $cleanedLabel = preg_replace('/[*\s]+$/', '', $cleanedLabel);

                                            // Skip jika ini field wajib
                                            if (isRequiredField($cleanedLabel, $requiredKeywords)) {
                                                continue;
                                            }

                                            // Cek apakah field ini sudah ada di database
                                            $fieldExists = false;
                                            foreach($fields as $f) {
                                                if (strtolower(trim($f->label)) === strtolower(trim($cleanedLabel))) {
                                                    $fieldExists = true;
                                                    break;
                                                }
                                            }

                                            // Skip jika field sudah ada di database (karena sudah diproses di loop sebelumnya)
                                            if ($fieldExists) continue;

                                            // Decode URL-encoded value
                                            $decodedValue = $value;
                                            $iterations = 0;

                                            while (strpos($decodedValue, '%') !== false && $iterations < $maxIterations) {
                                                $newValue = urldecode($decodedValue);
                                                if ($newValue === $decodedValue) break;
                                                $decodedValue = $newValue;
                                                $iterations++;
                                            }

                                            // Bersihkan nilai
                                            $decodedValue = is_string($decodedValue) ? str_replace(["\r\n", "\r", "\n"], " ", $decodedValue) : $decodedValue;
                                        @endphp

                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 group">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="mb-2">
                                                        <div class="field-label-display text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $cleanedLabel }}
                                                        </div>
                                                        <input type="text"
                                                            placeholder="Nama Field"
                                                            value="{{ $cleanedLabel }}"
                                                            class="field-label hidden block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                                            onblur="updateFieldLabel(this)"
                                                            onkeypress="handleFieldLabelKeyPress(event, this)"
                                                            data-required="false">
                                                    </div>
                                                    <div>
                                                        <input type="text"
                                                            placeholder="Value"
                                                            name="data[{{ $cleanedLabel }}]"
                                                            value="{{ old('data.' . $cleanedLabel, $decodedValue) }}"
                                                            class="field-value block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                                    </div>
                                                </div>
                                                <button type="button"
                                                    class="delete-field ml-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity"
                                                    onclick="deleteField(this)">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button id="addFieldBtn" type="button" class="mt-4 w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Field
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 mt-6">
                            <a href="{{ route('dashboard') }}" class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Error -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">Terjadi Kesalahan</h3>
                <button onclick="closeErrorModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="errorModalContent" class="mb-4 text-gray-700 dark:text-gray-300">
                <!-- Pesan error akan ditampilkan di sini -->
            </div>
            <div class="text-right">
                <button onclick="closeErrorModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addFieldBtn = document.getElementById('addFieldBtn');
            const editForm = document.getElementById('editForm');

            addFieldBtn.addEventListener('click', function() {
                addFormField();
            });

            // Inisialisasi format harga pada load halaman
            initPriceFormatting();

            // Tambahkan event listener untuk semua label yang bisa diedit
            setupLabelClickHandlers();

            // Handler untuk submit form
            editForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah form submit standar

                // Validasi form
                if (validateForm()) {
                    // Jika valid, lakukan submit menggunakan AJAX
                    submitFormAjax(this);
                }
            });
        });

        // Fungsi untuk validasi form
        function validateForm() {
            // Periksa field wajib
            let requiredFields = document.querySelectorAll('.field-label[data-required="true"]');
            let isValid = true;
            let errorMessages = [];

            requiredFields.forEach(function(field) {
                const group = field.closest('.group');
                const valueInput = group.querySelector('.field-value');
                const label = field.value || field.closest('.mb-2').querySelector('.field-label-display').textContent.trim();

                if (!valueInput.value.trim()) {
                    isValid = false;
                    valueInput.classList.add('border-red-500');
                    errorMessages.push('Field "' + label + '" tidak boleh kosong');
                } else {
                    valueInput.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                showErrorModal('Mohon isi semua field wajib!', errorMessages);
            }

            return isValid;
        }

        // Fungsi untuk submit form via AJAX
        function submitFormAjax(form) {
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const submitBtn = form.querySelector('button[type="submit"]');

            // Debugging
            console.log('Submitting form to:', url);
            console.log('Form data keys:', [...formData.keys()]);

            // Nonaktifkan tombol submit untuk mencegah submit berulang
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            // Tambahkan CSRF token secara manual jika belum ada
            if (!formData.has('_token')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                formData.append('_token', csrfToken);
            }

            // Pastikan method PUT disertakan
            if (!formData.has('_method')) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.redirected) {
                    // Jika server mengirim redirect, ikuti redirect
                    console.log('Redirecting to:', response.url);
                    window.location.href = response.url;
                    return null;
                }

                return response.text().then(text => {
                    try {
                        return text ? JSON.parse(text) : null;
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        console.log('Raw response:', text);
                        return { error: 'Gagal memproses respons dari server' };
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);

                if (data && data.error) {
                    // Jika ada error, tampilkan di modal
                    showErrorModal('Gagal menyimpan perubahan', [data.error]);
                } else if (data && data.success) {
                    // Jika ada data sukses, redirect ke URL yang diberikan
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = "{{ route('dashboard') }}";
                    }
                } else if (data === null) {
                    // Response kosong atau redirect sudah ditangani
                    // Do nothing, browser akan diarahkan oleh redirect sebelumnya
                } else {
                    // Response tidak berformat yang diharapkan
                    // Coba reload halaman dashboard
                    window.location.href = "{{ route('dashboard') }}";
                }
            })
            .catch(error => {
                // Handle error
                console.error('Fetch error:', error);
                showErrorModal('Terjadi kesalahan', ['Tidak dapat menghubungi server. Silakan coba lagi nanti.']);
            })
            .finally(() => {
                // Aktifkan kembali tombol submit
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Perubahan';
            });
        }

        // Fungsi untuk menampilkan modal error
        function showErrorModal(title, errorList) {
            const modal = document.getElementById('errorModal');
            const content = document.getElementById('errorModalContent');

            // Set judul error
            content.innerHTML = `<p class="font-semibold">${title}</p>`;

            // Tambahkan daftar error jika ada
            if (errorList && errorList.length > 0) {
                const ul = document.createElement('ul');
                ul.className = 'list-disc pl-5 mt-2';

                errorList.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    ul.appendChild(li);
                });

                content.appendChild(ul);
            }

            // Tampilkan modal
            modal.classList.remove('hidden');
        }

        // Fungsi untuk menutup modal error
        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.add('hidden');
        }

        // Fungsi untuk memformat angka dengan pemisah ribuan
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Inisialisasi format untuk field harga
        function initPriceFormatting() {
            // Cari semua input field
            document.querySelectorAll('.field-value').forEach(input => {
                const label = input.getAttribute('name');
                if (label && label.toLowerCase().includes('harga')) {
                    setupPriceField(input);
                }
            });
        }

        // Setup field harga dengan validasi dan formatting
        function setupPriceField(input) {
            // Atur atribut input untuk hanya angka
            input.setAttribute('inputmode', 'numeric');
            // Hapus attribute pattern agar tidak menampilkan "please match the requested format"
            // input.setAttribute('pattern', '[0-9]*');

            // Format nilai awal
            if (input.value) {
                // Hapus karakter non-angka dan format ulang
                let value = input.value.replace(/\D/g, '');
                if (value) {
                    input.value = formatRupiah(value);
                }
            }

            // Event listener untuk input
            input.addEventListener('input', function(e) {
                // Hapus karakter non-angka
                let value = e.target.value.replace(/\D/g, '');

                // Format dengan pemisah ribuan
                if (value) {
                    e.target.value = formatRupiah(value);
                }
            });

            // Event listener untuk mencegah input karakter non-angka
            input.addEventListener('keypress', function(e) {
                const charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        }

        function addFormField(label = '', value = '') {
            const formContainer = document.getElementById('formFields');
            const fieldId = 'field_' + Date.now();
            const isWajibField = ['tanggal', 'produk', 'harga'].includes(label.toLowerCase());

            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 group';
            fieldDiv.innerHTML = `
                <div class="flex items-start justify-between">
                    <div class="flex-grow">
                        <div class="mb-2">
                            <div class="field-label-display text-sm font-medium text-gray-700 dark:text-gray-300 ${isWajibField ? '' : 'cursor-pointer hover:text-blue-600 dark:hover:text-blue-400'}">
                                ${label || 'Klik untuk menambah nama field'}${isWajibField ? '<span class="text-red-500"> *</span>' : ''}
                            </div>
                            <input type="text"
                                   placeholder="Nama Field"
                                   value="${label}"
                                   class="field-label hidden block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                   ${isWajibField ? 'readonly' : ''}
                                   onblur="updateFieldLabel(this)"
                                   onkeypress="handleFieldLabelKeyPress(event, this)"
                                   data-required="${isWajibField}">
                        </div>
                        <div>
                            <input type="text"
                                   placeholder="Value"
                                   value="${value}"
                                   class="field-value block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                   ${isWajibField ? 'required' : ''}>
                        </div>
                    </div>
                    <button type="button"
                            class="delete-field ml-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="deleteField(this)"
                            ${isWajibField ? 'disabled style="display: none;"' : ''}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

            formContainer.appendChild(fieldDiv);

            // Tambahkan event listener untuk label yang baru dibuat
            const labelDisplay = fieldDiv.querySelector('.field-label-display');
            if (labelDisplay && labelDisplay.classList.contains('cursor-pointer')) {
                labelDisplay.addEventListener('click', handleLabelClick);
            }

            // Dapatkan elemen input nilai
            const valueInput = fieldDiv.querySelector('.field-value');

            // Tambahkan atribut name dengan name kosong sementara
            valueInput.name = 'data[' + (label || 'temp_' + fieldId) + ']';

            // Jika label mengandung kata "harga", terapkan formatting
            if (label && label.toLowerCase().includes('harga')) {
                setupPriceField(valueInput);
            }

            return fieldDiv;
        }

        function editFieldLabel(element) {
            // Temukan div.mb-2 yang berisi element ini
            const container = element.closest('.mb-2');

            // Jika div.mb-2 ditemukan
            if (container) {
                // Ambil input field dan sembunyikan display element
                const input = container.querySelector('.field-label');
                if (input) {
                    element.classList.add('hidden');
                    input.classList.remove('hidden');
                    input.focus();
                }
            }
        }

        function updateFieldLabel(input) {
            const container = input.parentElement;

            // Dapatkan display element
            const display = container.querySelector('.field-label-display');
            const value = input.value.trim();
            const isRequired = input.getAttribute('data-required') === 'true';

            // Update display text
            if (display) {
                display.textContent = value || 'Klik untuk menambah nama field';

                // Add asterisk if required
                if (isRequired) {
                    let asterisk = display.querySelector('span');
                    if (!asterisk) {
                        asterisk = document.createElement('span');
                        asterisk.className = 'text-red-500';
                        asterisk.textContent = ' *';
                        display.appendChild(asterisk);
                    }
                }

                display.classList.remove('hidden');
                input.classList.add('hidden');
            }

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

        // Setup event handler untuk semua label field
        function setupLabelClickHandlers() {
            document.querySelectorAll('.field-label-display').forEach(label => {
                if (label.classList.contains('cursor-pointer')) {
                    label.removeEventListener('click', handleLabelClick); // Hapus handler lama jika ada
                    label.addEventListener('click', handleLabelClick);
                }
            });
        }

        // Handler untuk klik pada label
        function handleLabelClick(event) {
            const element = event.currentTarget;
            const container = element.closest('.mb-2') || element.parentElement;
            const input = container.querySelector('.field-label');

            if (input) {
                element.classList.add('hidden');
                input.classList.remove('hidden');
                input.focus();
            }
        }
    </script>
    @endpush
</x-app-layout>
