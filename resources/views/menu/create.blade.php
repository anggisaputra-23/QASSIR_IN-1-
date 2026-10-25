@extends('layouts.app')

@section('content')
<h3><i class="bi bi-plus-circle"></i> Tambah Menu</h3>

<form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Gambar (opsional)</label>
        <input type="file" name="gambar" class="form-control">
    </div>
    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
    <a href="{{ route('menu.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
