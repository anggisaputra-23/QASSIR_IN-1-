<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToTransaksisTable extends Migration
{
    public function up(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}

}
