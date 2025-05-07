<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrukField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'type',
        'is_required',
        'order'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'order' => 'integer'
    ];

    public function values(): HasMany
    {
        return $this->hasMany(StrukValue::class);
    }
} 