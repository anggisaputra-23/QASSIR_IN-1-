<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasHarian;
use App\Models\Transaksi;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function bukaKasirForm()
    {
        $hariIni = Carbon::today()->toDateString();
        $kas = KasHarian::where('tanggal', $hariIni)->first();
        return view('kasir.buka', compact('kas'));
    }

    public function bukaKasir(Request $request)
    {
        $request->validate([
            'kas_awal' => 'required|integer|min:0'
        ]);

        $hariIni = Carbon::today()->toDateString();

        KasHarian::updateOrCreate(
            ['tanggal' => $hariIni],
            ['kas_awal' => $request->kas_awal, 'ditutup' => false]
        );

        return redirect()->route('dashboard')->with('success', 'Kasir berhasil dibuka!');
    }

    public function tutupKasir()
    {
        $hariIni = Carbon::today()->toDateString();

        $transaksiHariIni = Transaksi::whereDate('created_at', $hariIni)->get();
        $pendapatan = $transaksiHariIni->sum('total_harga');

        $kas = KasHarian::where('tanggal', $hariIni)->first();
        if (!$kas) {
            return redirect()->back()->with('error', 'Kas hari ini belum dibuka.');
        }

        $kas->update([
            'pendapatan' => $pendapatan,
            'total_akhir' => $kas->kas_awal + $pendapatan,
            'ditutup' => true
        ]);

        return redirect()->route('dashboard')->with('success', 'Kasir berhasil ditutup.');
    }
}
