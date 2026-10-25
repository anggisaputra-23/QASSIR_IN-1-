<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransaksiController extends Controller
{
    /**
     * Tampilkan halaman utama transaksi.
     */
    public function index()
    {
        $menus = Menu::whereNull('deleted_at')->get();
        return view('transaksi.index', compact('menus'));
    }

    /**
     * Proses checkout dan simpan transaksi kasir.
     */
    public function checkout(Request $request)
    {
        // Tangani jika request berasal dari fetch() sebagai JSON
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        // Validasi dasar
        if (!isset($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
            return response()->json(['success' => false, 'message' => 'Data item tidak valid.'], 422);
        }

        if (!isset($data['uang_dibayar']) || !is_numeric($data['uang_dibayar'])) {
            return response()->json(['success' => false, 'message' => 'Jumlah uang bayar tidak valid.'], 422);
        }

        $total = 0;
        $items = $data['items'];

        // Hitung total dan validasi menu
        foreach ($items as $item) {
            if (!isset($item['id'], $item['jumlah']) || $item['jumlah'] < 1) {
                return response()->json(['success' => false, 'message' => 'Data item tidak lengkap.'], 422);
            }

            $menu = Menu::find($item['id']);
            if (!$menu) {
                return response()->json(['success' => false, 'message' => "Menu ID {$item['id']} tidak ditemukan."], 404);
            }

            $total += $menu->harga * $item['jumlah'];
        }

        $kembalian = $data['uang_dibayar'] - $total;

        if ($kembalian < 0) {
            return response()->json(['success' => false, 'message' => 'Uang bayar tidak mencukupi.'], 422);
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'total_harga'   => $total,
            'uang_dibayar'  => $data['uang_dibayar'],
            'kembalian'     => $kembalian,
            'atas_nama'     => $data['atas_nama'] ?? null,
            'tipe_pesan'    => $data['tipe_pesan'] ?? null,
            'nomor_meja'    => $data['nomor_meja'] ?? null,
            'metode_bayar'  => $data['metode_bayar'] ?? 'tunai',
            'self_order'    => false,
            'diproses'      => true,
        ]);

        // Simpan detail transaksi
        foreach ($items as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'menu_id'      => $item['id'],
                'jumlah'       => $item['jumlah'],
                'catatan'      => $item['catatan'] ?? null,
            ]);
        }

        // Beri response jika dari JavaScript
        if ($request->expectsJson() || $request->isJson()) {
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil']);
        }

        // Fallback untuk submit biasa
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Tampilkan riwayat transaksi non-self-order.
     */
    public function riwayat()
    {
        $transaksis = Transaksi::with('detailTransaksis.menu')
            ->where('self_order', false)
            ->latest()
            ->get()
            ->groupBy(fn($trx) => $trx->created_at->format('Y-m-d'));

        $riwayatPerHari = $transaksis->map(function ($items, $tanggal) {
            return [
                'tanggal'     => Carbon::parse($tanggal)->translatedFormat('d F Y'),
                'transaksis'  => $items,
            ];
        });

        return view('transaksi.riwayat', compact('riwayatPerHari'));
    }

    /**
     * Cetak struk transaksi.
     */
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with('detailTransaksis.menu')->findOrFail($id);
        return view('transaksi.struk', compact('transaksi'));
    }

    /**
     * Batalkan transaksi (jika masih hari yang sama).
     */
    public function batalkan($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->created_at->isToday()) {
            $transaksi->delete();
            return back()->with('success', 'Transaksi berhasil dibatalkan.');
        }

        return back()->with('error', 'Transaksi hanya bisa dibatalkan pada hari yang sama.');
    }
}
