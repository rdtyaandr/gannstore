<?php

namespace Database\Seeders;

use App\Models\StrukField;
use Illuminate\Database\Seeder;

class StrukFieldSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar field dasar yang umum ditemukan di struk
        $commonFields = [
            // Field utama (wajib)
            ['name' => 'tanggal', 'label' => 'Tanggal', 'is_required' => true],
            ['name' => 'produk', 'label' => 'Produk', 'is_required' => true],
            ['name' => 'harga', 'label' => 'Harga', 'is_required' => true],
        ];

        // Buat field menggunakan firstOrCreate untuk menghindari duplikasi
        foreach ($commonFields as $index => $fieldInfo) {
            StrukField::firstOrCreate(
                ['name' => $fieldInfo['name']],
                [
                    'label' => $fieldInfo['label'],
                    'type' => 'text',
                    'is_required' => $fieldInfo['is_required'],
                    'order' => $index + 1
                ]
            );
        }
    }
}
