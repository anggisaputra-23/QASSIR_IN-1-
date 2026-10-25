<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('self_orders', function (Blueprint $table) {
            $table->id();
            $table->string('atas_nama');
            $table->string('tipe_pesan');
            $table->string('nomor_meja')->nullable();
            $table->string('metode_bayar');
            $table->decimal('total_harga', 10, 2);
            $table->boolean('diproses')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('self_orders');
    }
};
