<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Struk') }}
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
                            <!-- Link untuk tampilan mobile -->
                            <a href="#" id="imageLink" class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer md:hidden flex items-center">
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

                            <div class="flex justify-end mt-6 space-x-2">
                                <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                    Kembali
                                </a>
                                <button id="createButton" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Buat
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal untuk menampilkan gambar -->
                    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white p-4 rounded-lg max-w-3xl max-h-full overflow-auto">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold">Preview Struk</h3>
                                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
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

            // Buat form fields dari data yang sudah diproses
            for (const [label, value] of Object.entries(formData)) {
                addFormField(label, value);
            }
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
                            <div class="field-label-display text-sm font-medium text-gray-700 cursor-pointer hover:text-blue-600" onclick="editFieldLabel(this)">${label || 'Klik untuk menambah nama field'}</div>
                            <input type="text"
                                   placeholder="Nama Field"
                                   value="${label}"
                                   class="field-label hidden block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   onblur="updateFieldLabel(this)"
                                   onkeypress="handleFieldLabelKeyPress(event, this)">
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

        function deleteField(button) {
            const fieldDiv = button.closest('.group');
            fieldDiv.remove();
        }
    </script>
    @endpush
</x-app-layout>
