<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Struk extends Model
{
    protected $fillable = [
        'user_id',
        'image_path',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function values()
    {
        return $this->hasMany(StrukValue::class);
    }

    public function getValue($fieldName)
    {
        $field = StrukField::where('name', $fieldName)->first();
        if (!$field) {
            return null;
        }

        $value = $this->values()->where('struk_field_id', $field->id)->first();
        if ($value) {
            // Pastikan karakter URL-encoded telah di-decode
            return $this->decodeStrukValue($value->value);
        }

        // Coba ambil dari data JSON jika tersedia
        if (isset($this->data[$fieldName])) {
            return $this->decodeStrukValue($this->data[$fieldName]);
        }

        // Coba ambil berdasarkan label field (alternatif)
        if ($field && isset($this->data[$field->label])) {
            return $this->decodeStrukValue($this->data[$field->label]);
        }

        return null;
    }

    /**
     * Decode nilai struk yang mengandung karakter URL-encoded
     * Metode ini akan menangani karakter %0D%0A (CR LF) dengan benar
     */
    public function decodeStrukValue($value)
    {
        if (empty($value)) {
            return $value;
        }

        // Decode nilai sampai tidak ada lagi karakter URL-encoded
        $decodedValue = $value;
        while (strpos($decodedValue, '%') !== false) {
            $newValue = urldecode($decodedValue);
            // Jika tidak ada perubahan setelah decode, hentikan loop
            if ($newValue === $decodedValue) {
                break;
            }
            $decodedValue = $newValue;
        }

        return $decodedValue;
    }

    /**
     * Override metode getAttribute untuk mengubah cara array data diakses
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // Jika key adalah 'data' dan merupakan array, decode semua nilai di dalamnya
        if ($key === 'data' && is_array($value)) {
            $decodedData = [];
            foreach ($value as $dataKey => $dataValue) {
                $decodedKey = $this->decodeStrukValue($dataKey);
                $decodedValue = $this->decodeStrukValue($dataValue);
                $decodedData[$decodedKey] = $decodedValue;
            }
            return $decodedData;
        }

        return $value;
    }
}
