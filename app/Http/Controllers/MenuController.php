<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $menus = Menu::when($keyword, function ($query, $keyword) {
            return $query->where('nama', 'LIKE', "%{$keyword}%");
        })->latest()->get();

        return view('menu.index', compact('menus', 'keyword'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $namaFile = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('gambar_menu'), $namaFile);
            $validated['gambar'] = $namaFile;
        }

        Menu::create($validated);
        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Menu $menu)
    {
        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($menu->gambar && File::exists(public_path('gambar_menu/' . $menu->gambar))) {
                File::delete(public_path('gambar_menu/' . $menu->gambar));
            }

            $namaFile = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('gambar_menu'), $namaFile);
            $validated['gambar'] = $namaFile;
        }

        $menu->update($validated);
        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar && File::exists(public_path('gambar_menu/' . $menu->gambar))) {
            File::delete(public_path('gambar_menu/' . $menu->gambar));
        }

        $menu->delete();
        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus');
    }
}
