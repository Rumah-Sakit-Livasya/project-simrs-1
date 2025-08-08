<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stored_barang_non_farmasi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('pbi_id')->constrained("warehouse_penerimaan_barang_non_farmasi_item")->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained("warehouse_master_gudang")->onDelete('cascade');
            $table->integer('qty')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stored_barang_non_farmasi');
    }
};
