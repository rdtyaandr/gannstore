<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scan Struk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Preview Gambar -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Preview Gambar</h3>
                            <!-- Link untuk tampilan mobile -->
                            <a href="#" id="imageLink" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline cursor-pointer md:hidden flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Klik untuk melihat gambar struk
                            </a>
                            <!-- Gambar untuk tampilan desktop -->
                            <img id="previewImage" src="{{ $imageBase64 }}" alt="Preview Struk" class="hidden md:block w-full rounded-lg shadow">
                        </div>

                        <!-- Hasil OCR dan Form -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Rincian Transaksi</h3>

                            <!-- Form Fields -->
                            <div id="formFields" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                                <div class="space-y-4">
                                    <!-- Fields will be added here dynamically -->
                                </div>
                                <button id="addFieldBtn" class="mt-4 w-full bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Field
                                </button>
                            </div>

                            <div class="flex justify-end mt-6 space-x-2">
                                <a href="{{ route('dashboard') }}" class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                                    Kembali
                                </a>
                                <button id="createButton" class="bg-green-500 dark:bg-green-600 text-white px-4 py-2 rounded hover:bg-green-600 dark:hover:bg-green-700">
                                    Buat
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal untuk menampilkan gambar -->
                    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg max-w-3xl max-h-full overflow-auto">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold dark:text-gray-200">Preview Struk</h3>
                                <button id="closeModal" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <img id="modalImage" src="{{ $imageBase64 }}" alt="Preview Struk" class="w-full rounded-lg">
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
            const imageLink = document.getElementById('imageLink');
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const closeModal = document.getElementById('closeModal');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const loadingText = document.getElementById('loadingText');
            const addFieldBtn = document.getElementById('addFieldBtn');
            const createButton = document.getElementById('createButton');

            // Buka modal saat link diklik
            imageLink.addEventListener('click', function(e) {
                e.preventDefault();
                imageModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Mencegah scroll pada body
            });

            // Tutup modal saat tombol close diklik
            closeModal.addEventListener('click', function() {
                imageModal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Mengaktifkan kembali scroll
            });

            // Tutup modal saat mengklik diluar konten modal
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal) {
                    imageModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });

            // Event listener untuk tombol tambah field
            addFieldBtn.addEventListener('click', () => {
                addFormField();
            });

            // Event listener untuk tombol buat
            createButton.addEventListener('click', async () => {
                // Validasi field wajib
                const wajibFields = ['Tanggal', 'Produk', 'Harga'];
                const missingFields = [];

                wajibFields.forEach(wajibField => {
                    const fieldElements = [...document.querySelectorAll('.field-label-display')];
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

                // Jika ada field wajib yang kosong, tampilkan peringatan dan berhenti
                if (missingFields.length > 0) {
                    showFieldWarningModal(missingFields);
                    return;
                }

                const formData = {};
                document.querySelectorAll('.group').forEach(field => {
                    const label = field.querySelector('.field-label-display').textContent;
                    const value = field.querySelector('.field-value').value;
                    if (label !== 'Klik untuk menambah nama field') {
                        formData[label] = value;
                    }
                });

                try {
                    loadingOverlay.classList.remove('hidden');
                    loadingText.textContent = 'Menyimpan data...';

                    const response = await fetch('{{ route("struks.storeFromScan") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            tempPath: '{{ $tempPath }}',
                            data: formData
                        })
                    });

                    // Log response untuk debug
                    console.log('Response status:', response.status);

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Response error:', errorText);
                        throw new Error(`Server merespons dengan status ${response.status}`);
                    }

                    const result = await response.json();
                    console.log('Response success:', result);

                    if (result.success) {
                        // Hapus alert dan langsung redirect
                        window.location.href = result.redirect_url || '{{ route("dashboard") }}';
                    } else {
                        throw new Error(result.message || 'Gagal menyimpan data');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Tetap log error ke console tapi jangan tampilkan alert
                } finally {
                    loadingOverlay.classList.add('hidden');
                }
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

                // Tampilkan hasil OCR di console
                console.log('Hasil OCR Mentah:', text);
                console.log('-------------------');

                const formattedText = processOCRResult(text);

                // Tampilkan hasil setelah diformat
                console.log('Hasil OCR Setelah Diformat:', formattedText);
                console.log('-------------------');

                // Siapkan form fields
                prepareFormFields(formattedText);

                // Inisialisasi format harga
                initPriceFormatting();
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

            const lines = cleanedText.split('\n');
            const orderedResults = [];

            // Lewati bagian header dan cari semua baris yang memiliki format "key : value"
            let isHeader = true;
            let lastKey = "";
            let foundHarga = false;
            let isReadingSNRef = false;  // Flag untuk membaca SN/Ref
            let snRefValue = "";        // Nilai untuk SN/Ref

            // Fungsi untuk membulatkan harga sesuai aturan
            function roundPrice(price) {
                // Hapus karakter non-numerik (koma, titik, dll) dan konversi ke angka
                let numPrice = parseFloat(price.replace(/[^\d]/g, ''));

                // Bulatkan ke ribuan terdekat (naik)
                let roundedToThousand = Math.ceil(numPrice / 1000) * 1000;

                // Tambahkan 1000
                let finalPrice = roundedToThousand + 1000;

                // Format dengan titik setiap 3 angka
                return finalPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].trim();

                // Jika sudah menemukan Harga, keluar dari loop
                if (foundHarga) {
                    break;
                }

                // Lewati baris kosong
                if (!line || line === '-' || line === '&' || line === '--') {
                    continue;
                }

                // Jika menemukan "Rincian Transaksi", mulai memproses data
                if (line.includes('Rincian Transaksi')) {
                    isHeader = false;
                    continue;
                }

                // Jika sedang membaca nilai SN/Ref, gabungkan dengan nilai sebelumnya
                if (isReadingSNRef) {
                    // Jika menemukan line dengan "Harga", proses harga dan hentikan membaca SN/Ref
                    if (line.toLowerCase().includes('harga rp')) {
                        // Simpan SN/Ref yang sudah dikumpulkan
                        orderedResults.push(`SN/Ref : ${snRefValue.trim()}`);
                        isReadingSNRef = false;

                        // Proses harga
                        const priceMatch = line.match(/Harga\s+Rp\.?\s*([0-9.,]+)/i);
                        if (priceMatch && priceMatch[1]) {
                            const originalPrice = priceMatch[1].trim();
                            const roundedPrice = roundPrice(originalPrice);
                            orderedResults.push(`Harga : ${roundedPrice}`);
                            foundHarga = true;
                            continue;
                        }
                    } else {
                        // Tambahkan baris ini ke SN/Ref value
                        snRefValue += " " + line;
                        continue;
                    }
                }

                // Deteksi field Harga tanpa tanda ":"
                if (!isHeader && line.toLowerCase().includes('harga rp')) {
                    // Jika masih membaca SN/Ref, simpan nilainya dulu
                    if (isReadingSNRef) {
                        orderedResults.push(`SN/Ref : ${snRefValue.trim()}`);
                        isReadingSNRef = false;
                    }

                    const priceMatch = line.match(/Harga\s+Rp\.?\s*([0-9.,]+)/i);
                    if (priceMatch && priceMatch[1]) {
                        const originalPrice = priceMatch[1].trim();
                        const roundedPrice = roundPrice(originalPrice);
                        orderedResults.push(`Harga : ${roundedPrice}`);
                        foundHarga = true;
                        continue;
                    }
                }

                // Jika tidak lagi di header dan baris mengandung ":"
                if (!isHeader && line.includes(':')) {
                    const parts = line.split(':');
                    const key = parts[0].trim();
                    const value = parts.slice(1).join(':').trim();
                    lastKey = key;

                    // Jika ini adalah SN/Ref, mulai membaca semua baris berikutnya sebagai bagian dari SN/Ref
                    if (key.toUpperCase().includes('SN/REF')) {
                        isReadingSNRef = true;
                        snRefValue = value;
                        continue;
                    }

                    // Jika ini adalah field harga, tandai bahwa kita sudah menemukan harga
                    if (key.toLowerCase().includes('harga')) {
                        // Jika masih membaca SN/Ref, simpan nilainya dulu
                        if (isReadingSNRef) {
                            orderedResults.push(`SN/Ref : ${snRefValue.trim()}`);
                            isReadingSNRef = false;
                        }

                        let priceValue = value.replace(/rp\.?/i, '').trim();
                        const roundedPrice = roundPrice(priceValue);
                        orderedResults.push(`${key} : ${roundedPrice}`);
                        foundHarga = true;
                        continue;
                    }

                    // Perbaiki format tanggal jika ini adalah field tanggal
                    if (key.includes('Tanggal')) {
                        let dateValue = value;
                        const dateMatch = dateValue.match(/(\d{2})[\/\-](\d{2})[\/\-](\d{4})\s+(\d{2}):?(\d{2})?/);
                        if (dateMatch) {
                            const hour = dateMatch[4];
                            const minute = dateMatch[5] || '00';
                            dateValue = `${dateMatch[1]}/${dateMatch[2]}/${dateMatch[3]} ${hour}:${minute}`;
                            orderedResults.push(`${key} : ${dateValue}`);
                        } else {
                            orderedResults.push(`${key} : ${value}`);
                        }
                    } else if (!isReadingSNRef) {
                        // Hanya tambahkan field baru jika tidak sedang membaca SN/Ref
                        orderedResults.push(`${key} : ${value}`);
                    }
                }
                // PENTING: Jika baris ini tidak mengandung ":" dan bukan bagian header, maka ini adalah lanjutan dari field sebelumnya
                else if (!isHeader && !line.includes(':')) {
                    // Jika ini adalah baris pertama setelah Rincian Transaksi, lewati
                    if (orderedResults.length > 0 && !isReadingSNRef) {
                        // Periksa apakah baris ini berisi "Harga Rp"
                        if (line.toLowerCase().includes('harga rp')) {
                            const priceMatch = line.match(/Harga\s+Rp\.?\s*([0-9.,]+)/i);
                            if (priceMatch && priceMatch[1]) {
                                const originalPrice = priceMatch[1].trim();
                                const roundedPrice = roundPrice(originalPrice);
                                orderedResults.push(`Harga : ${roundedPrice}`);
                                foundHarga = true;
                                continue;
                            }
                        }

                        // Ambil hasil terakhir, tambahkan spasi dan isi baris ini
                        const lastResult = orderedResults.pop();
                        const newResult = lastResult + ' ' + line.trim();
                        orderedResults.push(newResult);
                    }
                }
            }

            // Jika masih membaca SN/Ref tapi sudah sampai akhir, tambahkan ke hasil
            if (isReadingSNRef && snRefValue) {
                orderedResults.push(`SN/Ref : ${snRefValue.trim()}`);
            }

            // Jika tidak menemukan field apapun, gunakan pendekatan alternatif
            if (orderedResults.length === 0) {
                console.log("Menggunakan pendekatan alternatif untuk ekstraksi data");
                for (const line of lines) {
                    if (line.includes(':') && !line.includes('Rincian Transaksi')) {
                        orderedResults.push(line);
                    }
                }
            }

            return orderedResults.join('\n');
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

            // Buat objek untuk menyimpan data
            const formData = {};

            // Variabel untuk menampung baris sebelumnya
            let previousLabel = '';

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].trim();
                if (!line) continue;

                // Periksa apakah baris ini berisi tanda ":"
                if (line.includes(':')) {
                    // Bagi menjadi label dan nilai (hanya di titik ':' pertama)
                    const parts = line.split(':');
                    const label = parts[0].trim();
                    // Gabungkan semua bagian setelah ':' pertama (untuk menangani nilai yang mengandung ':')
                    const value = parts.slice(1).join(':').trim();

                    formData[label] = value;
                    previousLabel = label;
                } else if (previousLabel && !line.includes(':')) {
                    // Ini adalah lanjutan dari nilai sebelumnya
                    formData[previousLabel] += ' ' + line;
                }
            }

            // Cek dan perbaiki format tanggal jika perlu
            if (formData['Tanggal']) {
                // Pastikan format waktu lengkap (HH:MM)
                const dateMatch = formData['Tanggal'].match(/(\d{2})\/(\d{2})\/(\d{4})\s+(\d{2}):?(\d{2})?/);
                if (dateMatch) {
                    const hour = dateMatch[4];
                    const minute = dateMatch[5] || '00';
                    formData['Tanggal'] = `${dateMatch[1]}/${dateMatch[2]}/${dateMatch[3]} ${hour}:${minute}`;
                }
            }

            // Pastikan field wajib selalu ada
            const wajibFields = ['Tanggal', 'Produk', 'Harga'];
            let emptyRequiredFields = [];

            wajibFields.forEach(field => {
                if (!Object.keys(formData).some(key => key.toLowerCase() === field.toLowerCase())) {
                    formData[field] = ''; // Tambahkan field dengan nilai kosong
                    emptyRequiredFields.push(field);
                }
            });

            // Cek duplikat field dengan nama berbeda namun maksud sama
            const fieldMappings = {
                'tanggal': 'Tanggal',
                'tgl': 'Tanggal',
                'date': 'Tanggal',
                'produk': 'Produk',
                'product': 'Produk',
                'nama produk': 'Produk',
                'item': 'Produk',
                'harga': 'Harga',
                'price': 'Harga',
                'total': 'Harga',
                'jumlah': 'Harga'
            };

            // Normalisasi nama field berdasarkan mapping
            const normalizedData = {};
            for (const [key, value] of Object.entries(formData)) {
                const lowerKey = key.toLowerCase();
                const normalizedKey = fieldMappings[lowerKey] || key;
                normalizedData[normalizedKey] = value;
            }

            // Buat form fields dari data yang sudah diproses
            for (const [label, value] of Object.entries(normalizedData)) {
                addFormField(label, value);
            }

            // Tampilkan modal peringatan jika ada field wajib yang tidak terdeteksi/kosong
            if (emptyRequiredFields.length > 0) {
                setTimeout(() => {
                    showFieldWarningModal(emptyRequiredFields);
                }, 500);
            }
        }

        // Modal peringatan untuk field wajib yang kosong
        function showFieldWarningModal(emptyFields) {
            // Buat modal jika belum ada
            let warningModal = document.getElementById('fieldWarningModal');
            if (!warningModal) {
                warningModal = document.createElement('div');
                warningModal.id = 'fieldWarningModal';
                warningModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

                const modalHTML = `
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Perhatian</h3>
                            <button id="closeWarningModal" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mb-4">
                            <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 dark:border-yellow-600 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                            Field wajib berikut tidak terdeteksi atau kosong. Harap isi nilai pada semua field wajib.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <ul id="warningFieldList" class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-300">
                                    <!-- Daftar field akan diisi secara dinamis -->
                                </ul>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button id="confirmWarningBtn" class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800">
                                Saya Mengerti
                            </button>
                        </div>
                    </div>
                `;

                warningModal.innerHTML = modalHTML;
                document.body.appendChild(warningModal);

                // Event listeners untuk modal
                warningModal.querySelector('#closeWarningModal').addEventListener('click', function() {
                    warningModal.classList.add('hidden');
                });

                warningModal.querySelector('#confirmWarningBtn').addEventListener('click', function() {
                    warningModal.classList.add('hidden');
                });
            } else {
                warningModal.classList.remove('hidden');
            }

            // Isi daftar field yang kosong
            const listContainer = warningModal.querySelector('#warningFieldList');
            listContainer.innerHTML = '';

            emptyFields.forEach(field => {
                const li = document.createElement('li');
                li.textContent = field;
                listContainer.appendChild(li);
            });
        }

        function addFormField(label = '', value = '') {
            const formContainer = document.querySelector('#formFields .space-y-4');
            const fieldId = 'field_' + Date.now();
            const isWajibField = ['tanggal', 'produk', 'harga'].includes(label.toLowerCase());

            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 group';
            fieldDiv.innerHTML = `
                <div class="flex items-start justify-between">
                    <div class="flex-grow">
                        <div class="mb-2">
                            <div class="field-label-display text-sm font-medium text-gray-700 dark:text-gray-300 ${isWajibField ? '' : 'cursor-pointer hover:text-blue-600 dark:hover:text-blue-400'}" ${isWajibField ? '' : 'onclick="editFieldLabel(this)"'}>
                                ${label || 'Klik untuk menambah nama field'}${isWajibField ? '<span class="text-red-500"> *</span>' : ''}
                            </div>
                            <input type="text"
                                placeholder="Nama Field"
                                value="${label}"
                                class="field-label hidden block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                ${isWajibField ? 'readonly' : ''}
                                onblur="updateFieldLabel(this)"
                                onkeypress="handleFieldLabelKeyPress(event, this)">
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
                        class="delete-field ml-2 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity"
                        onclick="deleteField(this)"
                        ${isWajibField ? 'disabled style="display: none;"' : ''}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

            formContainer.appendChild(fieldDiv);

            // Jika label adalah harga, atur formatting angka
            const valueInput = fieldDiv.querySelector('.field-value');
            if (label && label.toLowerCase().includes('harga')) {
                setupPriceField(valueInput);
            }
        }

        function deleteField(button) {
            const fieldDiv = button.closest('.group');
            const labelDisplay = fieldDiv.querySelector('.field-label-display');
            const labelText = labelDisplay.textContent.trim().toLowerCase();
            const isWajibField = ['tanggal', 'produk', 'harga'].includes(labelText.replace(/\s+\*$/, '').toLowerCase());

            // Jangan izinkan penghapusan field wajib
            if (isWajibField) {
                alert('Field wajib tidak dapat dihapus!');
                return;
            }

            fieldDiv.remove();
        }

        // Fungsi untuk memformat angka dengan pemisah ribuan
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

            display.textContent = value || 'Klik untuk menambah nama field';
            display.classList.remove('hidden');
            input.classList.add('hidden');
        }

        function handleFieldLabelKeyPress(event, input) {
            if (event.key === 'Enter') {
                event.preventDefault();
                input.blur();
            }
        }

        // Inisialisasi format untuk field harga
        function initPriceFormatting() {
            // Cari semua input field
            document.querySelectorAll('.field-value').forEach(input => {
                const fieldLabel = input.closest('.group').querySelector('.field-label-display').textContent;
                if (fieldLabel && fieldLabel.toLowerCase().includes('harga')) {
                    setupPriceField(input);
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
