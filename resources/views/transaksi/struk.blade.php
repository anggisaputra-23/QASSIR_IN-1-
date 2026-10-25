<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi</title>
    <style>
        /* Gunakan font monospaced agar tampilan seperti kasir */
        body {
            font-family: monospace;
            font-size: 14px;
        }

        /* Rata tengah */
        .text-center {
            text-align: center;
        }

        /* Garis bawah putus-putus */
        .border-bottom {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    {{-- Header Struk --}}
    <div class="text-center">
        <h4>Qassir_IN</h4>
        <div>{{ now()->format('d M Y H:i') }}</div>
        <div class="border-bottom"></div>
    </div>

    {{-- Informasi Umum Transaksi --}}
    <div><strong>Transaksi #{{ $transaksi->id }}</strong></div>
    <div>Metode: {{ ucfirst($transaksi->metode_bayar) }}</div>

    <div class="border-bottom"></div>

    {{-- Daftar Item Transaksi --}}
    @foreach($transaksi->detailTransaksis as $item)
        <div>{{ $item->menu->nama }} ({{ $item->jumlah }})</div>
        @if($item->catatan)
            <div><small>Catatan: {{ $item->catatan }}</small></div>
        @endif
        <div style="text-align: right;">
            Rp{{ number_format($item->menu->harga * $item->jumlah, 0, ',', '.') }}
        </div>
    @endforeach

    <div class="border-bottom"></div>

    {{-- Ringkasan Pembayaran --}}
    <div><strong>Total:</strong> Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
    <div><strong>Dibayar:</strong> Rp{{ number_format($transaksi->uang_dibayar, 0, ',', '.') }}</div>
    <div><strong>Kembalian:</strong> Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}</div>

    <div class="border-bottom" style="margin-top: 10px;"></div>

    {{-- Footer --}}
    <div class="text-center">
        Terima kasih! <br>
        <small>{{ now()->format('d-m-Y H:i') }}</small>
    </div>

    {{-- Cetak otomatis saat halaman dibuka --}}
    <script>
        window.print();
    </script>
</body>
</html>
