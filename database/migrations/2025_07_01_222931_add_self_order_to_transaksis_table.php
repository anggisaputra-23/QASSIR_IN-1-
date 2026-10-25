<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->string('atas_nama')->nullable();
        $table->enum('tipe_pesan', ['Dine In', 'Take Away'])->nullable();
        $table->string('nomor_meja')->nullable();
        $table->enum('metode_bayar', ['tunai', 'non-tunai'])->nullable();
        $table->boolean('self_order')->default(false);
        $table->boolean('diproses')->default(false); // untuk status diproses kasir
    });
}




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            //
        });
    }
};
