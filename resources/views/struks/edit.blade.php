<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Struk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('struks.update', $struk) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <!-- Informasi Struk -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Detail Struk</h3>

                                <!-- Field Dinamis Berdasarkan Data Struk -->
                                <div id="formFields" class="space-y-4">
                                    @php
                                        // Dapatkan data dari field values dan gabungkan dengan data JSON
                                        $strukData = $struk->data ?? [];

                                        // Ambil semua field yang wajib dan tampilkan terlebih dahulu
                                        $requiredFields = $fields->where('is_required', true)->sortBy('order');
                                        $optionalFields = $fields->where('is_required', false)->sortBy('order');
                                    @endphp

                                    <!-- Field Wajib -->
                                    @foreach($requiredFields as $field)
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 group">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="mb-2">
                                                        <div class="field-label-display text-sm font-medium text-gray-700 cursor-pointer hover:text-blue-600" onclick="editFieldLabel(this)">
                                                            {{ $field->label }} <span class="text-red-500">*</span>
                                                        </div>
                                                        <input type="text"
                                                            placeholder="Nama Field"
                                                            value="{{ $field->label }}"
                                                            class="field-label hidden block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            onblur="updateFieldLabel(this)"
                                                            onkeypress="handleFieldLabelKeyPress(event, this)"
                                                            data-required="true">
                                                    </div>
                                                    <div>
                                                        <input type="text"
                                                            placeholder="Value"
                                                            name="data[{{ $field->label }}]"
                                                            value="{{ old('data.' . $field->label, $struk->getValue($field->name) ?? ($strukData[$field->label] ?? '')) }}"
                                                            class="field-value block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Field Opsional -->
                                    <div class="mt-6 mb-3 pt-3 border-t border-gray-200">
                                        <h4 class="text-md font-medium text-gray-700">Informasi Tambahan</h4>
                                    </div>

                                    @foreach($optionalFields as $field)
                                        @php
                                            $fieldValue = $struk->getValue($field->name) ?? ($strukData[$field->label] ?? '');
                                        @endphp

                                        @if($fieldValue)
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 group">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-grow">
                                                        <div class="mb-2">
                                                            <div class="field-label-display text-sm font-medium text-gray-700 cursor-pointer hover:text-blue-600" onclick="editFieldLabel(this)">
                                                                {{ $field->label }}
                                                            </div>
                                                            <input type="text"
                                                                placeholder="Nama Field"
                                                                value="{{ $field->label }}"
                                                                class="field-label hidden block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                                onblur="updateFieldLabel(this)"
                                                                onkeypress="handleFieldLabelKeyPress(event, this)"
                                                                data-required="false">
                                                        </div>
                                                        <div>
                                                            <input type="text"
                                                                placeholder="Value"
                                                                name="data[{{ $field->label }}]"
                                                                value="{{ old('data.' . $field->label, $fieldValue) }}"
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
                                            </div>
                                        @endif
                                    @endforeach

                                    <!-- Tambahan Field Kustom yang mungkin tidak ada di database -->
                                    @foreach($strukData as $label => $value)
                                        @php
                                            $fieldExists = $fields->where('label', $label)->first();
                                        @endphp

                                        @if(!$fieldExists && !empty($value))
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 group">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-grow">
                                                        <div class="mb-2">
                                                            <div class="field-label-display text-sm font-medium text-gray-700 cursor-pointer hover:text-blue-600" onclick="editFieldLabel(this)">
                                                                {{ $label }}
                                                            </div>
                                                            <input type="text"
                                                                placeholder="Nama Field"
                                                                value="{{ $label }}"
                                                                class="field-label hidden block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                                onblur="updateFieldLabel(this)"
                                                                onkeypress="handleFieldLabelKeyPress(event, this)"
                                                                data-required="false">
                                                        </div>
                                                        <div>
                                                            <input type="text"
                                                                placeholder="Value"
                                                                name="data[{{ $label }}]"
                                                                value="{{ old('data.' . $label, $value) }}"
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
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <button id="addFieldBtn" type="button" class="mt-4 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Field
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 mt-6">
                            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addFieldBtn = document.getElementById('addFieldBtn');

            addFieldBtn.addEventListener('click', function() {
                addFormField();
            });
        });

        function addFormField(label = '', value = '') {
            const formContainer = document.getElementById('formFields');
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
                                   onkeypress="handleFieldLabelKeyPress(event, this)"
                                   data-required="false">
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

            // Dapatkan elemen input nilai
            const valueInput = fieldDiv.querySelector('.field-value');

            // Tambahkan atribut name dengan name kosong sementara
            valueInput.name = 'data[' + (label || 'temp_' + fieldId) + ']';

            return fieldDiv;
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

        // Tambahkan handler untuk form submit
        document.getElementById('editForm').addEventListener('submit', function(e) {
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
                e.preventDefault();
                alert('Mohon isi semua field wajib!');
            }
        });
    </script>
    @endpush
</x-app-layout>
