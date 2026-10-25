<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['nama' => 'SINGLE ESPRESSO', 'harga' => 10000],
            ['nama' => 'DOUBLE ESPRESSO', 'harga' => 15000],
            ['nama' => 'AMERICANO', 'harga' => 18000],
            ['nama' => 'CAPPUCINO', 'harga' => 18000],
            ['nama' => 'CAFE LATTE', 'harga' => 20000],
            ['nama' => 'AFFOGATO', 'harga' => 20000],
            ['nama' => 'MOLASE ROCK', 'harga' => 22000],
            ['nama' => 'KOPSU VANILLA', 'harga' => 20000],
            ['nama' => 'KOPSU AJIBARANG', 'harga' => 20000],
            ['nama' => 'KOPSU MATCHA', 'harga' => 20000],
            ['nama' => 'KOPSU HAZELNUT', 'harga' => 20000],
            ['nama' => 'MOCHA MINT', 'harga' => 20000],
            ['nama' => 'CARAMEL MACHIATO', 'harga' => 20000],
            ['nama' => 'NASI GORENG KUPI', 'harga' => 26000],
            ['nama' => 'NASI GORENG AYAM', 'harga' => 25000],
            ['nama' => 'NASI GORENG SOSIS', 'harga' => 25000],
            ['nama' => 'NASI GORENG KENCUR', 'harga' => 25000],
            ['nama' => 'MIE GORENG', 'harga' => 25000],
            ['nama' => 'MIE TEK TEK', 'harga' => 25000],
            ['nama' => 'BUTTER RICE CHICKEN CURRY', 'harga' => 26000],
            ['nama' => 'RICE CURRY CHICKEN KATSU', 'harga' => 26000],
            ['nama' => 'FRENCH FRIES', 'harga' => 15000],
            ['nama' => 'FRENCH FRIES CHEESE', 'harga' => 18000],
            ['nama' => 'POTATO WEDGES', 'harga' => 20000],
            ['nama' => 'OTAK OTAK', 'harga' => 15000],
            ['nama' => 'CIRENG', 'harga' => 15000],
            ['nama' => 'NANA CHOCO', 'harga' => 18000],
            ['nama' => 'NANA CHEESE', 'harga' => 18000],
            ['nama' => 'NANA CHOCOCHEESE', 'harga' => 20000],
            ['nama' => 'ONION RING', 'harga' => 20000],
            ['nama' => "CRISPY CHIX 'N CHEESE", 'harga' => 20000],
            ['nama' => 'MIX PLATTER', 'harga' => 38000],
        ];

        foreach ($menus as $menu) {
            Menu::create([
                'nama' => $menu['nama'],
                'harga' => $menu['harga'],
                'deskripsi' => '-', // opsional: bisa diisi keterangan singkat
            ]);
        }
    }
}
