<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preview Hasil OCR') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Preview Gambar -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Preview Gambar</h3>
                            <img id="previewImage" src="{{ $imageBase64 }}" alt="Preview Struk" class="w-full rounded-lg shadow">
                        </div>

                        <!-- Hasil OCR dan Form -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Hasil Pembacaan</h3>

                            <!-- Form Fields -->
                            <div id="formFields" class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="space-y-4">
                                    <!-- Fields will be added here dynamically -->
                                </div>
                                <button id="addFieldBtn" class="mt-4 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Field
                                </button>
                            </div>

                            <div class="flex justify-end mt-6">
                                <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Overlay -->
                    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
                            <p class="text-white mt-4 text-lg" id="loadingText">Memproses gambar...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/tesseract.js@v2.1.0/dist/tesseract.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const image = document.getElementById('previewImage');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const loadingText = document.getElementById('loadingText');
            const addFieldBtn = document.getElementById('addFieldBtn');

            // Event listener untuk tombol tambah field
            addFieldBtn.addEventListener('click', () => {
                addFormField();
            });

            // Tampilkan loading overlay
            loadingOverlay.classList.remove('hidden');

            Tesseract.recognize(
                image.src,
                'ind',
                {
                    logger: m => {
                        if (m.status === 'recognizing text') {
                            const progress = Math.floor(m.progress * 100);
                            loadingText.textContent = `Memproses: ${progress}%`;
                        }
                    }
                }
            ).then(({ data: { text } }) => {
                // Sembunyikan loading overlay
                loadingOverlay.classList.add('hidden');
                const formattedText = processOCRResult(text);

                // Siapkan form fields
                prepareFormFields(formattedText);
            }).catch(err => {
                // Sembunyikan loading overlay
                loadingOverlay.classList.add('hidden');
                alert('Gagal memproses gambar: ' + err.message);
            });
        });

        function processOCRResult(text) {
            // Membersihkan dan memformat teks
            let cleanedText = text
                .replace(/\r\n/g, '\n')  // Normalisasi line endings
                .replace(/\n{3,}/g, '\n\n')  // Hapus multiple newlines
                .trim();

            // Ekstrak informasi penting
            const lines = cleanedText.split('\n');
            let startIndex = -1;
            let endIndex = -1;

            // Cari indeks mulai dan selesai
            for (let i = 0; i < lines.length; i++) {
                if (lines[i].includes('Rincian Transaksi')) {
                    startIndex = i;
                }
                if (lines[i].includes('Harga')) {
                    endIndex = i;
                    break;
                }
            }

            // Jika ditemukan range yang valid
            if (startIndex !== -1 && endIndex !== -1) {
                const relevantLines = lines.slice(startIndex, endIndex);
                const processedLines = relevantLines
                    .map(line => {
                        // Hapus kata kunci yang tidak diperlukan
                        line = line.replace('Rincian Transaksi', '');

                        // Perbaiki format tanggal
                        if (line.includes('Tanggal')) {
                            return line.replace(/(\d{2})(\d{2})(\d{4})/, '$1/$2/$3');
                        }

                        // Perbaiki format angka
                        if (line.includes('Daya Token')) {
                            // Ganti format angka dari "12,.6kwh" menjadi "12,6kwh"
                            return line.replace(/(\d+),\.(\d+)kwh/i, '$1,$2kwh');
                        }
                        // Hapus karakter khusus yang tidak diinginkan
                        return line.replace(/[^\w\s\d.,:;()-]/g, '');
                    })
                    .filter(line => line.trim() !== ''); // Hapus baris kosong

                return processedLines.join('\n');
            }

            return cleanedText; // Return semua teks jika tidak menemukan range yang valid
        }

        function prepareFormFields(text) {
            const lines = text.split('\n');
            const formContainer = document.querySelector('#formFields .space-y-4');

            if (!formContainer) {
                console.log('Form container not found');
                return;
            }

            // Bersihkan container yang ada
            formContainer.innerHTML = '';

            lines.forEach(line => {
                if (line.trim()) {
                    const [label, value] = line.split(':').map(part => part.trim());
                    if (label && value) {
                        addFormField(label, value);
                    }
                }
            });
        }

        function addFormField(label = '', value = '') {
            const formContainer = document.querySelector('#formFields .space-y-4');
            const fieldId = 'field_' + Date.now();

            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200 group';
            fieldDiv.innerHTML = `
                <div class="flex items-start justify-between">
                    <div class="flex-grow">
                        <div class="mb-2">
                            <input type="text"
                                   placeholder="Nama Field"
                                   value="${label}"
                                   class="field-label block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   onchange="updateFieldLabel(this)">
                        </div>
                        <div>
                            <input type="text"
                                   placeholder="Value"
                                   value="${value}"
                                   class="field-value block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

            formContainer.appendChild(fieldDiv);
        }

        function updateFieldLabel(input) {
            const value = input.value.trim();
            if (!value) {
                input.value = 'Field Baru';
            }
        }

        function deleteField(button) {
            if (confirm('Apakah Anda yakin ingin menghapus field ini?')) {
                const fieldDiv = button.closest('.group');
                fieldDiv.remove();
            }
        }
    </script>
    @endpush
</x-app-layout>