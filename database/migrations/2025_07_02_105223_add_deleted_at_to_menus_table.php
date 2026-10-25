<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToMenusTable extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at nullable
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
