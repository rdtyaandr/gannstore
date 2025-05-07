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
                Upload Gambar Otomatis
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
                                <th class="p-3 text-left">Screenshot</th>
                                    @foreach($fields as $field)
                                        <th class="p-3 text-left">{{ $field->label }}</th>
                                    @endforeach
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($struks as $struk)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                            @if($struk->screenshot_path)
                                        <img src="{{ Storage::url($struk->screenshot_path) }}" alt="Struk" class="h-12 w-auto rounded">
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                    </td>
                                        @foreach($fields as $field)
                                            <td class="p-3">{{ $struk->getValue($field->name) }}</td>
                                        @endforeach
                                    <td class="p-3 flex space-x-2">
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
                <h2 class="text-xl font-semibold">Upload Gambar Otomatis</h2>
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
                        Upload
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
                <h2 class="text-xl font-semibold">Buat Data Manual</h2>
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

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal('manual')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan semua modal berada di bawah body untuk menghindari masalah z-index
            document.querySelectorAll('.modal').forEach(function(modal) {
                document.body.appendChild(modal);
            });
        });
        
        const fields = @json($fields);
        
        function openModal(type) {
            const modal = document.getElementById(type + 'Modal');
            modal.classList.remove('hidden');
            modal.classList.add('show');
            
            if (type === 'upload') {
                renderFields('uploadFields');
            } else if (type === 'manual') {
                renderFields('manualFields');
            }
        }

        function closeModal(type) {
            const modal = document.getElementById(type + 'Modal');
            modal.classList.remove('show');
            modal.classList.add('hidden');
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



