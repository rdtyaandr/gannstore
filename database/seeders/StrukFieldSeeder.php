<?php

namespace Database\Seeders;

use App\Models\StrukField;
use Illuminate\Database\Seeder;

class StrukFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            [
                'name' => 'product_name',
                'label' => 'Nama Produk',
                'type' => 'text',
                'is_required' => true,
                'order' => 1
            ],
            [
                'name' => 'original_price',
                'label' => 'Harga Asli (AgenPulsa)',
                'type' => 'number',
                'is_required' => true,
                'order' => 2
            ],
            [
                'name' => 'gannstore_price',
                'label' => 'Harga GannStore',
                'type' => 'number',
                'is_required' => true,
                'order' => 3
            ]
        ];

        foreach ($fields as $field) {
            StrukField::create($field);
        }
    }
} 