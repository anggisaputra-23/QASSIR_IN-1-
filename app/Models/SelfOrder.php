<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfOrder extends Model
{
    protected $table = 'selforder'; // pastikan ini cocok dengan nama tabel kamu

    protected $fillable = [
        'atas_nama',
        'tipe_pesan',
        'nomor_meja',
        'metode_bayar',
        'total_harga',
        'uang_dibayar',
        'kembalian',
        'diproses',
    ];
}
