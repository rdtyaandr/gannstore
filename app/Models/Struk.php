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
            return $value->value;
        }

        // Coba ambil dari data JSON jika tersedia
        if (isset($this->data[$fieldName])) {
            return $this->data[$fieldName];
        }

        return null;
    }
}
