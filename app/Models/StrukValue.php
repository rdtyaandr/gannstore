<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrukValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'struk_id',
        'struk_field_id',
        'value'
    ];

    public function struk(): BelongsTo
    {
        return $this->belongsTo(Struk::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(StrukField::class, 'struk_field_id');
    }
} 