<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_barang_non_farmasi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nama');
            $table->string('kode');
            $table->string('keterangan')->nullable();
            $table->integer('hna')->comment("harga beli");
            $table->integer('ppn')->comment("ppn beli (%)");
            $table->boolean('aktif')->default(true);
            $table->boolean('jual_pasien')->default(false);
            $table->foreignId('kategori_id')->constrained('warehouse_kategori_barang')->onDelete('cascade');
            $table->foreignId('golongan_id')->nullable()->constrained('warehouse_golongan_barang')->onDelete('cascade');
            $table->foreignId('kelompok_id')->nullable()->constrained('warehouse_kelompok_barang')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_barang_non_farmasi');
    }
};
