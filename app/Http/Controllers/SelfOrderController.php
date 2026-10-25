<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class SelfOrderController extends Controller
{
    // ✅ Formulir Self Order
    public function form()
    {
        $menus = Menu::whereNull('deleted_at')->get();
        return view('pelanggan.order', compact('menus'));
    }

    // ✅ Menyimpan pesanan Self Order
    public function store(Request $request)
    {
        $request->validate([
            'atas_nama'     => 'required|string|max:100',
            'tipe_pesan'    => 'required|in:Dine In,Take Away',
            'metode_bayar'  => 'required|in:tunai,non-tunai',
            'menus'         => 'required|array|min:1',
        ]);

        if ($request->tipe_pesan === 'Dine In' && empty($request->nomor_meja)) {
            return back()->withErrors(['nomor_meja' => 'Nomor Meja wajib diisi untuk Dine In.'])->withInput();
        }

        $total = 0;
        $validMenus = [];

        foreach ($request->menus as $menuId => $data) {
            $menu = Menu::find($menuId);
            $jumlah = intval($data['jumlah'] ?? 0);

            if (!$menu || $jumlah <= 0) continue;

            $total += $menu->harga * $jumlah;
            $validMenus[] = [
                'menu'    => $menu,
                'jumlah'  => $jumlah,
                'catatan' => $data['catatan'] ?? null,
            ];
        }

        if ($total <= 0 || count($validMenus) === 0) {
            return back()->withErrors(['menus' => 'Tidak ada menu valid yang dipesan.'])->withInput();
        }

        // ✅ Simpan transaksi
        $transaksi = Transaksi::create([
            'total_harga'   => $total,
            'uang_dibayar'  => $request->metode_bayar === 'non-tunai' ? $total : null,
            'kembalian'     => $request->metode_bayar === 'non-tunai' ? 0 : null,
            'atas_nama'     => $request->atas_nama,
            'tipe_pesan'    => $request->tipe_pesan,
            'nomor_meja'    => $request->tipe_pesan === 'Dine In' ? $request->nomor_meja : null,
            'metode_bayar'  => $request->metode_bayar,
            'self_order'    => true,
            'diproses'      => $request->metode_bayar === 'non-tunai',
        ]);

        // ✅ Simpan detail menu
        foreach ($validMenus as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'menu_id'      => $item['menu']->id,
                'jumlah'       => $item['jumlah'],
                'catatan'      => $item['catatan'],
            ]);
        }

        // ✅ Redirect ke halaman konfirmasi
        return redirect()->route('selforder.konfirmasi', $transaksi->id);
    }

    // ✅ Halaman konfirmasi setelah kirim
    public function konfirmasi($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('pelanggan.konfirmasi', compact('transaksi'));
    }

    // ✅ Admin: Daftar pesanan self order
    public function adminIndex()
    {
        $selfOrders = Transaksi::where('self_order', true)
            ->with('detailTransaksis.menu')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.selforders', compact('selfOrders'));
    }

    // ✅ Admin: Proses pesanan
    public function proses($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->diproses = true;
        $transaksi->save();

        return back()->with('success', 'Pesanan berhasil diproses.');
    }

    // ✅ Admin: Batalkan pesanan self order
    public function batalkan($id)
    {
        $transaksi = Transaksi::where('self_order', true)->findOrFail($id);

        if ($transaksi->created_at->isToday() && !$transaksi->diproses) {
            $transaksi->delete(); // Soft delete
            return back()->with('success', 'Pesanan self order berhasil dibatalkan.');
        }

        return back()->with('error', 'Pesanan hanya bisa dibatalkan pada hari yang sama dan jika belum diproses.');
    }

    // ✅ Halaman struk (invoice)
    public function invoice($id)
    {
        $transaksi = Transaksi::with('detailTransaksis.menu')->findOrFail($id);

        // Cegah akses jika belum diproses
        if (!$transaksi->diproses) {
            return redirect()->route('selforder.konfirmasi', $id)
                ->with('error', 'Pesanan belum dikonfirmasi oleh kasir.');
        }

        return view('pelanggan.invoice', compact('transaksi'));
    }
}
