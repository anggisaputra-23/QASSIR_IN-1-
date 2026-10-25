@extends('layouts.app')

@section('content')
<h3><i class="bi bi-cart4"></i> Transaksi</h3>

<!-- Pencarian -->
<div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Cari menu..." oninput="filterMenu()">
</div>

<div class="row">
    <!-- KOLOM MENU -->
    <div class="col-md-8">
        <div class="row" id="menuContainer">
            @foreach ($menus as $menu)
            <div class="menu-item col-md-4 mb-3" data-nama="{{ strtolower($menu->nama) }}">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        @if ($menu->gambar)
                        <img src="{{ asset('gambar_menu/' . $menu->gambar) }}" class="img-fluid mb-2"
                            style="max-height: 100px; object-fit: cover;">
                        @endif
                        <h5 class="card-title">{{ $menu->nama }}</h5>
                        <p class="text-muted small">{{ $menu->deskripsi }}</p>
                        <p class="card-text fw-bold">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-success btn-sm"
                            onclick="addToCart({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})">
                            <i class="bi bi-plus-circle"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- KOLOM KERANJANG -->
    <div class="col-md-4">
        <div class="position-sticky" style="top: 80px;">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-basket"></i> Keranjang
                </div>
                <div class="card-body" id="cart-items">
                    <p class="text-muted">Keranjang kosong</p>
                </div>
                <div class="card-footer">
                    <div id="total-harga" class="mb-2 fw-bold text-end">Total: Rp0</div>

                    <div id="bayarSection" style="display: none;">
                        <div id="otomatisUang" class="d-flex flex-wrap gap-2 mb-2"></div>
                        <input type="number" class="form-control mb-2" id="uangBayar"
                            placeholder="Jumlah uang dibayarkan" oninput="hitungKembalian()">
                        <div class="text-end fw-bold">Kembalian: <span id="kembalian">Rp0</span></div>
                    </div>

                    <!-- Tombol Checkout -->
                    <button type="button" class="btn btn-warning w-100 mt-2" onclick="checkout()">
                        <i class="bi bi-bag-check"></i> Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Transaksi Sukses -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle-fill"></i> Transaksi Berhasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="successModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];

function addToCart(id, nama, harga) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.jumlah++;
    } else {
        cart.push({ id, nama, harga, jumlah: 1, catatan: '' });
    }
    renderCart();
}

function updateJumlah(index, delta) {
    cart[index].jumlah += delta;
    if (cart[index].jumlah <= 0) {
        cart.splice(index, 1);
    }
    renderCart();
}

function updateCatatan(index, value) {
    cart[index].catatan = value;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function getTotalCart() {
    return cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const totalDiv = document.getElementById('total-harga');
    const bayarSection = document.getElementById('bayarSection');

    if (cart.length === 0) {
        container.innerHTML = '<p class="text-muted">Keranjang kosong</p>';
        totalDiv.innerText = 'Total: Rp0';
        bayarSection.style.display = 'none';
        document.getElementById('kembalian').innerText = 'Rp0';
        return;
    }

    let total = 0;
    let html = '';
    cart.forEach((item, index) => {
        const subtotal = item.harga * item.jumlah;
        total += subtotal;
        html += `
            <div class="border rounded p-2 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>${item.nama}</strong>
                    <span>Rp${subtotal.toLocaleString()}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <button class="btn btn-sm btn-secondary" onclick="updateJumlah(${index}, -1)">-</button>
                        <span class="mx-2">${item.jumlah}</span>
                        <button class="btn btn-sm btn-secondary" onclick="updateJumlah(${index}, 1)">+</button>
                    </div>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <input type="text" class="form-control form-control-sm mt-2" placeholder="Catatan (opsional)"
                    value="${item.catatan}" oninput="updateCatatan(${index}, this.value)">
            </div>
        `;
    });

    container.innerHTML = html;
    totalDiv.innerText = 'Total: Rp' + total.toLocaleString();
    bayarSection.style.display = 'block';

    generatePilihanUang(total);
    hitungKembalian();
}

function generatePilihanUang(total) {
    const otomatisUang = document.getElementById('otomatisUang');
    otomatisUang.innerHTML = '';
    const pilihan = [10000, 20000, 50000, 100000, 150000, 200000, 500000];
    const minimal = pilihan.filter(nominal => nominal >= total).slice(0, 4);
    minimal.forEach(nominal => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-outline-secondary btn-sm';
        btn.innerText = 'Rp' + nominal.toLocaleString();
        btn.onclick = () => {
            document.getElementById('uangBayar').value = nominal;
            hitungKembalian();
        };
        otomatisUang.appendChild(btn);
    });
}

function hitungKembalian() {
    const total = getTotalCart();
    const bayar = parseInt(document.getElementById('uangBayar').value || 0);
    const kembali = bayar - total;
    document.getElementById('kembalian').innerText = 'Rp' + (kembali > 0 ? kembali.toLocaleString() : 0);
}

function checkout() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    const total = getTotalCart();
    const bayar = parseInt(document.getElementById('uangBayar').value || 0);

    if (bayar < total) {
        alert('Uang tidak mencukupi!');
        return;
    }

    fetch('{{ route("checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: cart,
            uang_dibayar: bayar
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('HTTP error');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccessModal();
            cart = [];
            document.getElementById('uangBayar').value = '';
            renderCart();
        } else {
            alert('Gagal transaksi: ' + (data.message || ''));
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat menghubungi server.');
        console.error(error);
    });
}

function showSuccessModal() {
    let html = `<ul class="list-group mb-3">`;
    let total = 0;
    cart.forEach(item => {
        const subtotal = item.harga * item.jumlah;
        total += subtotal;
        html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${item.nama} (${item.jumlah}x)
                <span>Rp${subtotal.toLocaleString()}</span>
            </li>`;
    });

    const bayar = parseInt(document.getElementById('uangBayar').value || 0);
    const kembali = bayar - total;

    html += `</ul>
        <h5 class="text-end">Total: <strong>Rp${total.toLocaleString()}</strong></h5>
        <h6 class="text-end">Uang Bayar: Rp${bayar.toLocaleString()}</h6>
        <h6 class="text-end text-success">Kembalian: Rp${kembali.toLocaleString()}</h6>`;

    document.getElementById('successModalBody').innerHTML = html;
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
}

function filterMenu() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const items = document.querySelectorAll('.menu-item');
    items.forEach(item => {
        const nama = item.getAttribute('data-nama');
        item.style.display = nama.includes(keyword) ? 'block' : 'none';
    });
}
</script>
@endpush
