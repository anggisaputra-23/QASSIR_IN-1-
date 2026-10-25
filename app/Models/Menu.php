<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
     use SoftDeletes;
    // ✅ IZINKAN atribut ini diisi secara massal (create/update)
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'gambar',
    ];
}
