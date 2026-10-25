<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Transaksi;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        // 📊 Ringkasan Pendapatan
        $today = Carbon::today();
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)->sum('total_harga');
        $pendapatanBulanIni = Transaksi::whereMonth('created_at', $today->month)->sum('total_harga');
        $pendapatanTotal = Transaksi::sum('total_harga');

        // 📈 Grafik Pendapatan 7 Hari Terakhir
        $tanggalPendapatan = [];
        $nilaiPendapatan = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $tanggalPendapatan[] = $tanggal->format('d M');
            $nilaiPendapatan[] = Transaksi::whereDate('created_at', $tanggal)->sum('total_harga');
        }

        // 🥇 Top 5 Produk Terlaris
        $produkTerlaris = DB::table('detail_transaksis')
            ->select('menu_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $menu = Menu::find($item->menu_id);
                return (object)[
                    'nama' => $menu?->nama ?? 'Tidak ditemukan',
                    'terjual' => $item->total_terjual,
                ];
            });

        // 🔔 Jumlah Self Order Belum Diproses
        $jumlahSelfOrderBelumDiproses = Transaksi::where('self_order', true)
            ->where('diproses', false)
            ->count();

        return view('dashboard', [
            'pendapatanHariIni'            => $pendapatanHariIni,
            'pendapatanBulanIni'           => $pendapatanBulanIni,
            'pendapatanTotal'              => $pendapatanTotal,
            'tanggalPendapatan'            => $tanggalPendapatan,
            'nilaiPendapatan'              => $nilaiPendapatan,
            'produkTerlaris'               => $produkTerlaris,
            'jumlahSelfOrderBelumDiproses' => $jumlahSelfOrderBelumDiproses,
        ]);
    }
}
