<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaksi extends Model
{
    use HasFactory;

    // Izinkan mass assignment
    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'jumlah',
        'catatan',
    ];

    // Relasi ke menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
