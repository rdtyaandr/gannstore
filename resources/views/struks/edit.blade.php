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
                    <form action="{{ route('struks.update', $struk) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="product_name" class="block text-gray-700">Nama Produk</label>
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $struk->product_name) }}" class="mt-1 block w-full p-2 border rounded" required>
                            @error('product_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="original_price" class="block text-gray-700">Harga Asli (AgenPulsa)</label>
                            <input type="number" step="0.01" name="original_price" id="original_price" value="{{ old('original_price', $struk->original_price) }}" class="mt-1 block w-full p-2 border rounded" required>
                            @error('original_price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gannstore_price" class="block text-gray-700">Harga GannStore</label>
                            <input type="number" step="0.01" name="gannstore_price" id="gannstore_price" value="{{ old('gannstore_price', $struk->gannstore_price) }}" class="mt-1 block w-full p-2 border rounded" required>
                            @error('gannstore_price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="screenshot" class="block text-gray-700">Screenshot Struk (Opsional)</label>
                            @if($struk->screenshot_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($struk->screenshot_path) }}" alt="Current Screenshot" class="h-32 w-auto rounded">
                                </div>
                            @endif
                            <input type="file" name="screenshot" id="screenshot" accept="image/*" class="mt-1 block w-full p-2 border rounded">
                            @error('screenshot')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 