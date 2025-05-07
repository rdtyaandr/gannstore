<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'struk_id',
        'produk',
        'tanggal',
        'harga_beli',
        'harga_jual',
        'keuntungan',
        'catatan',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke struk
    public function struk()
    {
        return $this->belongsTo(Struk::class);
    }
}
