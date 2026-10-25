@extends('layouts.app')

@section('content')
<h3><i class="bi bi-pencil-square"></i> Edit Menu</h3>

<form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="nama" class="form-control" value="{{ $menu->nama }}" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control">{{ $menu->deskripsi }}</textarea>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ $menu->harga }}" required>
    </div>
    <div class="mb-3">
        <label>Gambar Sekarang</label><br>
        @if($menu->gambar)
            <img src="{{ asset('gambar_menu/' . $menu->gambar) }}" width="80"><br><br>
        @else
            <p><i>Tidak ada gambar</i></p>
        @endif
        <input type="file" name="gambar" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
    <a href="{{ route('menu.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
