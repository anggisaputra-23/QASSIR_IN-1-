@extends('layouts.app')

@section('content')
<h3 class="mb-4"><i class="bi bi-table"></i> Pesanan Self Order</h3>

@if(session('success'))
    <div class="alert alert-success small shadow-sm">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger small shadow-sm">{{ session('error') }}</div>
@endif

@if($selfOrders->isEmpty())
    <div class="alert alert-info shadow-sm">Belum ada pesanan self order.</div>
@endif

@foreach($selfOrders as $order)
<div class="card mb-4 shadow-sm {{ $order->diproses ? '' : 'border-warning border-2' }}">
    <div class="card-header d-flex justify-content-between align-items-center {{ $order->diproses ? 'bg-light' : 'bg-warning-subtle' }}">
        <div>
            <strong>{{ $order->atas_nama }}</strong>
            <span class="badge bg-secondary">{{ $order->tipe_pesan }}</span>

            @if($order->nomor_meja)
                <span class="ms-2 text-muted small">Meja: {{ $order->nomor_meja }}</span>
            @endif

            @if($order->diproses)
                <span class="badge bg-success ms-2"><i class="bi bi-check-circle"></i> Sudah Diproses</span>
            @else
                <span class="badge bg-warning text-dark ms-2"><i class="bi bi-hourglass-split"></i> Belum Diproses</span>
            @endif
        </div>
        <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
    </div>

    <div class="card-body">
        {{-- Daftar Menu --}}
        <ul class="list-group mb-3 small">
            @foreach ($order->detailTransaksis as $detail)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $detail->menu->nama }}</strong>
                        <span class="ms-1 text-muted">({{ $detail->jumlah }}x)</span>
                        @if ($detail->catatan)
                            <div class="text-muted small">Catatan: {{ $detail->catatan }}</div>
                        @endif
                    </div>
                    <div class="text-end fw-semibold text-success">
                        Rp{{ number_format($detail->menu->harga * $detail->jumlah, 0, ',', '.') }}
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- Info dan Aksi --}}
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-1"><strong>Total:</strong> Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                <p class="mb-0"><strong>Metode Bayar:</strong> {{ ucfirst($order->metode_bayar) }}</p>
            </div>

            <div class="d-flex gap-2">
                {{-- Tombol Batalkan (jika belum diproses dan masih hari ini) --}}
                @if(!$order->diproses && $order->created_at->isToday())
                <form action="{{ route('admin.selforders.batal', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x-circle"></i> Batalkan
                    </button>
                </form>
                @endif

                {{-- Tombol Proses --}}
                <form action="{{ route('admin.selforders.proses', $order->id) }}" method="POST">
                    @csrf
                    @if($order->diproses)
                        <button class="btn btn-outline-success btn-sm" disabled>
                            <i class="bi bi-check-circle"></i> Sudah Diproses
                        </button>
                    @else
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-gear"></i> Proses Sekarang
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
