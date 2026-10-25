<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
    $table->id();
    $table->integer('total_harga');
    $table->integer('uang_dibayar')->nullable();
    $table->integer('kembalian')->nullable();
    $table->timestamps();
    $table->softDeletes(); //
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
