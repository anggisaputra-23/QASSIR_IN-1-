@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">🧾 Struk Pemesanan</h5>
            <p><strong>Nomor Transaksi:</strong> {{ $transaksi->id }}</p>
            <p><strong>Atas Nama:</strong> {{ $transaksi->atas_nama }}</p>
            <p><strong>Waktu:</strong> {{ $transaksi->created_at->format('d M Y H:i') }}</p>
            <p><strong>Tipe Pesan:</strong> {{ $transaksi->tipe_pesan }}
                @if($transaksi->nomor_meja) - Meja {{ $transaksi->nomor_meja }} @endif
            </p>
            <p><strong>Metode Bayar:</strong> {{ ucfirst($transaksi->metode_bayar) }}</p>

            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detailTransaksis as $item)
                    <tr>
                        <td>{{ $item->menu->nama }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp{{ number_format($item->menu->harga * $item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h5 class="text-end">Total: Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</h5>

            <div class="text-center mt-4">
                <button class="btn btn-sm btn-secondary" onclick="window.print()">🖨 Cetak Struk</button>
                <a href="{{ route('selforder.form') }}" class="btn btn-sm btn-primary">Kembali ke Pemesanan</a>
            </div>
        </div>
    </div>
</div>
@endsection
