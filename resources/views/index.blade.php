@extends('layouts.app')

@section('content')
<h3><i class="bi bi-phone"></i> Self Order</h3>

<form id="formSelfOrder">
    <div class="mb-3">
        <label>Atas Nama</label>
        <input type="text" name="atas_nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Metode Pesan</label>
        <select name="metode_pesan" class="form-select" onchange="toggleMeja()" required>
            <option value="Dine In">Dine In</option>
            <option value="Take Away">Take Away</option>
        </select>
    </div>

    <div class="mb-3" id="inputMeja">
        <label>Nomor Meja</label>
        <input type="text" name="nomor_meja" class="form-control">
    </div>

    <div class="mb-3">
        <label>Metode Bayar</label>
        <select name="metode_bayar" class="form-select" required>
            <option value="tunai">Tunai (bayar ke kasir)</option>
            <option value="non-tunai">Non Tunai (langsung diproses)</option>
        </select>
    </div>

    <h5>Menu</h5>
    <div class="row">
        @foreach ($menus as $menu)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                @if ($menu->gambar)
                <img src="{{ asset('gambar_menu/' . $menu->gambar) }}" class="card-img-top" style="height:120px; object-fit:cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $menu->nama }}</h5>
                    <p class="card-text">{{ $menu->deskripsi }}</p>
                    <p>Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                    <input type="number" min="0" class="form-control jumlah" placeholder="Jumlah" data-id="{{ $menu->id }}">
                    <input type="text" class="form-control mt-1 catatan" placeholder="Catatan (opsional)" data-id="{{ $menu->id }}">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-success">Kirim Pesanan</button>
</form>

@endsection

@push('scripts')
<script>
function toggleMeja() {
    const metode = document.querySelector('[name="metode_pesan"]').value;
    document.getElementById('inputMeja').style.display = metode === 'Dine In' ? 'block' : 'none';
}

document.getElementById('formSelfOrder').addEventListener('submit', function(e) {
    e.preventDefault();

    const items = [];
    document.querySelectorAll('.jumlah').forEach(jumlahInput => {
        const id = jumlahInput.dataset.id;
        const jumlah = parseInt(jumlahInput.value || 0);
        if (jumlah > 0) {
            items.push({
                id,
                jumlah,
                catatan: document.querySelector(`.catatan[data-id="${id}"]`).value
            });
        }
    });

    if (items.length === 0) {
        alert('Pilih setidaknya satu menu!');
        return;
    }

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.items = items;

    fetch('{{ route("selforder.submit") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert(res.message);
            location.reload();
        } else {
            alert(res.message || 'Gagal mengirim pesanan');
        }
    });
});
</script>
@endpush
