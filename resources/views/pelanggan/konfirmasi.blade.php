@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="text-center">
        <div class="mb-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
        </div>

        <h4 class="mb-2">Pesanan Berhasil Dikirim</h4>

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <p class="mb-1">Silakan lakukan pembayaran ke kasir.</p>
        <p class="text-muted small">Struk akan tersedia setelah pesanan Anda diproses oleh kasir.</p>

        <a href="{{ route('selforder.invoice', $transaksi->id) }}" class="btn btn-outline-primary btn-sm mt-3">
            <i class="bi bi-receipt"></i> Cek Status Struk
        </a>
    </div>
</div>
@endsection
