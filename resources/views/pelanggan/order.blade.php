@extends('layouts.app')

@section('content')
<style>
    .card-menu:hover {
        transform: scale(1.02);
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
    }
    .card-menu {
        transition: 0.2s;
    }
</style>

<div class="container-fluid">
    <h4 class="mb-4 text-center"><i class="bi bi-ui-checks-grid"></i> Pemesanan Mandiri (Self Order)</h4>

    @if(session('success'))
        <div class="alert alert-success small shadow-sm">{{ session('success') }}</div>
    @endif

    <form action="{{ route('selforder.store') }}" method="POST" onsubmit="return validateOrder()">
        @csrf

        {{-- 🧍 Form Pelanggan --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Atas Nama</label>
                <input type="text" name="atas_nama" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tipe Pesan</label>
                <select name="tipe_pesan" id="tipe_pesan" class="form-select form-select-sm" onchange="toggleMeja()" required>
                    <option value="">-- Pilih --</option>
                    <option value="Dine In">Dine In</option>
                    <option value="Take Away">Take Away</option>
                </select>
            </div>
            <div class="col-md-3" id="nomorMejaContainer" style="display: none;">
                <label class="form-label small fw-semibold">Nomor Meja</label>
                <input type="text" name="nomor_meja" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Metode Bayar</label>
                <select name="metode_bayar" class="form-select form-select-sm" required>
                    <option value="">-- Pilih --</option>
                    <option value="tunai">Bayar ke Kasir</option>
                    <option value="non-tunai">Bayar Sekarang</option>
                </select>
            </div>
        </div>

        <div class="row">
            {{-- 📦 Daftar Menu --}}
            <div class="col-md-8">
                <div class="row g-3">
                    @foreach ($menus as $menu)
                        <div class="col-md-4">
                            <div class="card card-menu h-100">
                                @if ($menu->gambar)
                                    <img src="{{ asset('gambar_menu/' . $menu->gambar) }}" class="card-img-top" style="height:130px; object-fit:cover;">
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1 text-truncate">{{ $menu->nama }}</h6>
                                    <p class="text-muted small mb-1">{{ Str::limit($menu->deskripsi, 40) }}</p>
                                    <p class="fw-bold text-success small">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>

                                    <div class="input-group input-group-sm mb-2">
                                        <button class="btn btn-outline-secondary" type="button" onclick="ubahJumlah({{ $menu->id }}, -1)">-</button>
                                        <input type="number" name="menus[{{ $menu->id }}][jumlah]" id="jumlah-{{ $menu->id }}" value="0" class="form-control text-center jumlah-input" data-id="{{ $menu->id }}" min="0">
                                        <button class="btn btn-outline-secondary" type="button" onclick="ubahJumlah({{ $menu->id }}, 1)">+</button>
                                    </div>
                                    <input type="text" name="menus[{{ $menu->id }}][catatan]" id="catatan-{{ $menu->id }}" class="form-control form-control-sm catatan-input" placeholder="Catatan (opsional)" data-id="{{ $menu->id }}">
                                    <input type="hidden" id="harga-{{ $menu->id }}" value="{{ $menu->harga }}">
                                    <input type="hidden" id="nama-{{ $menu->id }}" value="{{ $menu->nama }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 🛒 Keranjang --}}
            <div class="col-md-4">
                <div class="card sticky-top shadow-sm" style="top:70px;">
                    <div class="card-header bg-primary text-white">
                        <strong><i class="bi bi-cart3"></i> Keranjang</strong>
                    </div>
                    <div class="card-body p-3" id="keranjangList">
                        <p class="text-muted small">Belum ada item.</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Total:</strong>
                            <span id="totalHarga">Rp0</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearKeranjang()">
                                <i class="bi bi-trash"></i> Kosongkan Keranjang
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-send-check"></i> Kirim Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleMeja() {
    const tipe = document.getElementById('tipe_pesan').value;
    document.getElementById('nomorMejaContainer').style.display = tipe === 'Dine In' ? 'block' : 'none';
}

function ubahJumlah(id, delta) {
    const input = document.getElementById('jumlah-' + id);
    let value = parseInt(input.value || '0');
    value = Math.max(0, value + delta);
    input.value = value;
    updateKeranjang();
}

document.addEventListener('input', function (e) {
    if (e.target.classList.contains('jumlah-input') || e.target.classList.contains('catatan-input')) {
        updateKeranjang();
    }
});

function updateKeranjang() {
    let keranjang = '';
    let total = 0;
    let adaItem = false;

    document.querySelectorAll('.jumlah-input').forEach(input => {
        const id = input.dataset.id;
        const jumlah = parseInt(input.value || '0');
        if (jumlah > 0) {
            const nama = document.getElementById('nama-' + id).value;
            const harga = parseInt(document.getElementById('harga-' + id).value);
            const catatan = document.getElementById('catatan-' + id).value;
            const subtotal = harga * jumlah;
            total += subtotal;
            adaItem = true;

            keranjang += `
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <strong>${nama}</strong>
                        <span>${jumlah}x</span>
                    </div>
                    ${catatan ? `<div class="text-muted small">Catatan: ${catatan}</div>` : ''}
                    <div class="text-end small fw-bold text-success">Rp${subtotal.toLocaleString('id-ID')}</div>
                </div>`;
        }
    });

    document.getElementById('keranjangList').innerHTML = adaItem ? keranjang : '<p class="text-muted small">Belum ada item.</p>';
    document.getElementById('totalHarga').innerText = 'Rp' + total.toLocaleString('id-ID');
}

function clearKeranjang() {
    if (confirm("Yakin ingin mengosongkan keranjang?")) {
        document.querySelectorAll('.jumlah-input').forEach(input => input.value = 0);
        document.querySelectorAll('.catatan-input').forEach(input => input.value = '');
        updateKeranjang();
    }
}

function validateOrder() {
    let valid = false;
    document.querySelectorAll('.jumlah-input').forEach(input => {
        if (parseInt(input.value) > 0) valid = true;
    });
    if (!valid) {
        alert('Silakan pilih setidaknya satu menu.');
    }
    return valid;
}
</script>
@endpush
