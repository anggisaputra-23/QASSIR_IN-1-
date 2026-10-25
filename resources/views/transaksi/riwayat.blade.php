@extends('layouts.app')

@section('content')
<h3 class="mb-4"><i class="bi bi-clock-history"></i> Riwayat Transaksi</h3>

<!-- Form filter berdasarkan tanggal -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row row-cols-lg-auto g-3 align-items-center">
            <div class="col-12">
                <label for="tanggal" class="form-label mb-0">
                    <i class="bi bi-calendar-date"></i> Filter Tanggal:
                </label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('riwayat') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tampilkan pesan jika tidak ada transaksi -->
@if ($riwayatPerHari->isEmpty())
    <div class="alert alert-info shadow-sm">Tidak ada transaksi yang ditemukan.</div>
@endif

<!-- Looping per hari -->
@foreach ($riwayatPerHari as $riwayat)
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">
                <i class="bi bi-calendar-week"></i> {{ $riwayat['tanggal'] }}
            </h5>
            @php
                $totalHari = $riwayat['transaksis']->sum('total_harga');
            @endphp
            <span class="badge bg-success fs-6">
                Total: Rp{{ number_format($totalHari, 0, ',', '.') }}
            </span>
        </div>

        <!-- Looping setiap transaksi pada tanggal tersebut -->
        @foreach ($riwayat['transaksis'] as $transaksi)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light d-flex justify-content-between">
                    <span><strong>Transaksi #{{ $transaksi->id }}</strong></span>
                    <span class="text-muted">{{ $transaksi->created_at->format('H:i') }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        <!-- Detail menu tiap transaksi -->
                        @foreach ($transaksi->detailTransaksis as $detail)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    {{ $detail->menu->nama }}
                                    <span class="text-muted">({{ $detail->jumlah }}x)</span>
                                    @if ($detail->catatan)
                                        <br><small class="text-muted">Catatan: {{ $detail->catatan }}</small>
                                    @endif
                                </div>
                                <div>
                                    Rp{{ number_format($detail->menu->harga * $detail->jumlah, 0, ',', '.') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Ringkasan pembayaran -->
                    <div class="text-end small">
                        <div><strong>Total:</strong> Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                        <div><strong>Uang Dibayar:</strong> Rp{{ number_format($transaksi->uang_dibayar, 0, ',', '.') }}</div>
                        <div><strong>Kembalian:</strong> Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}</div>
                    </div>

                    <!-- Aksi tombol cetak dan batalkan -->
                    <div class="text-end mt-3">
                        <a href="{{ route('transaksi.struk', $transaksi->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer"></i> Cetak Struk
                        </a>

                        @if ($transaksi->created_at->isToday())
                        <form action="{{ route('transaksi.batal', $transaksi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-x-circle"></i> Batalkan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
@endsection
