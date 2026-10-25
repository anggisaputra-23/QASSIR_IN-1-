<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'total_harga',
        'uang_dibayar',
        'kembalian',
        'atas_nama',
        'tipe_pesan',
        'nomor_meja',
        'metode_bayar',
        'self_order',
        'diproses',
    ];

    // Cast kolom boolean untuk kemudahan logika
    protected $casts = [
        'self_order' => 'boolean',
        'diproses' => 'boolean',
    ];

    // Relasi: Transaksi memiliki banyak detail transaksi
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
