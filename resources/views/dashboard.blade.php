@extends('layouts.app')

@section('content')
<h3><i class="bi bi-house-door"></i> Dashboard</h3>
<p class="text-muted">Selamat datang di sistem pemesanan <strong>Qassir_IN</strong>.</p>

{{-- Notifikasi jika ada Self Order yang belum diproses --}}
@if($jumlahSelfOrderBelumDiproses > 0)
<div class="alert alert-warning shadow-sm d-flex align-items-center justify-content-between p-3 rounded">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-3 fs-4 text-dark"></i>
        <div>
            <strong>{{ $jumlahSelfOrderBelumDiproses }}</strong> pesanan <strong>Self Order</strong> belum diproses.
            Segera periksa daftar pesanan untuk menghindari penumpukan.
        </div>
    </div>
    <a href="{{ route('admin.selforders') }}" class="btn btn-sm btn-outline-dark">
        <i class="bi bi-eye"></i> Lihat Pesanan
    </a>
</div>
@endif

{{-- Ringkasan pendapatan --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h6 class="mb-1">Pendapatan Hari Ini</h6>
                <h4 class="mb-0">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h6 class="mb-1">Pendapatan Bulan Ini</h6>
                <h4 class="mb-0">Rp{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark shadow">
            <div class="card-body">
                <h6 class="mb-1">Total Pendapatan</h6>
                <h4 class="mb-0">Rp{{ number_format($pendapatanTotal, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- Grafik pendapatan dan daftar produk terlaris --}}
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-graph-up-arrow"></i> Grafik Pendapatan 7 Hari Terakhir
            </div>
            <div class="card-body" style="height: 300px">
                <canvas id="pendapatanChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning fw-bold">
                <i class="bi bi-star-fill"></i> Top 5 Produk Terlaris
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($produkTerlaris as $produk)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $produk->nama }}
                        <span class="badge bg-primary rounded-pill">{{ $produk->terjual }}x</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Belum ada transaksi</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Chart.js untuk grafik pendapatan --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('pendapatanChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($tanggalPendapatan) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($nilaiPendapatan) !!},
            fill: true,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            pointBackgroundColor: '#0d6efd'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
