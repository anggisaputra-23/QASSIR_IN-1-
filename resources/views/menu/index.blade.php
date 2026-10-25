@extends('layouts.app')

@section('content')
<h3><i class="bi bi-box-seam"></i> Kelola Produk</h3>

{{-- Form Pencarian --}}
<form action="{{ route('menu.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="Cari nama menu..." value="{{ request('keyword') }}">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
        @if(request('keyword'))
        <a href="{{ route('menu.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Reset</a>
        @endif
    </div>
</form>

{{-- Tombol Tambah Menu --}}
<a href="{{ route('menu.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Tambah Menu</a>

{{-- Notifikasi --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Tabel Menu --}}
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($menus as $menu)
        <tr>
            <td>{{ $menu->nama }}</td>
            <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
            <td>
                @if($menu->gambar)
                    <img src="{{ asset('gambar_menu/' . $menu->gambar) }}" width="60">
                @else
                    <i class="text-muted">-</i>
                @endif
            </td>
            <td>
                <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus menu ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted">Tidak ada menu ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
