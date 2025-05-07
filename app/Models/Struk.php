<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    protected $fillable = [
        'image_path',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
