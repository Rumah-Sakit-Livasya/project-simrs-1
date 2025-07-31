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
        Schema::create('warehouse_barang_farmasi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nama');
            $table->string('kode');
            $table->string('keterangan')->nullable();
            $table->integer('hna')->comment("harga beli");
            $table->integer('ppn')->comment("ppn beli (%)");
            $table->integer('ppn_rajal')->comment("ppn rawat jalan (%)");
            $table->integer('ppn_ranap')->comment("ppn rawat inap (%)");
            $table->enum("tipe", ["FN", "NFN"])->comment("FN = Formularium Nasional, NFN = Non Formularium Nasional");
            $table->enum("formularium", ["RS", "NRS"])->comment("RS = Formularium Rumah Sakit, NFN = Formularium Non Rumah Sakit");
            $table->enum("jenis_obat", ["paten", "generik"])->nullable();
            $table->enum("exp", ["1w", "2w", "3w", "1mo", "2mo", "3mo", "6mo"])->nullable()->comment("Expiration date. w = minggu, mo = bulan");
            $table->boolean('aktif')->default(true);
            $table->foreignId('kategori_id')->constrained('warehouse_kategori_barang')->onDelete('cascade');
            $table->foreignId('golongan_id')->nullable()->constrained('warehouse_golongan_barang')->onDelete('cascade');
            $table->foreignId('kelompok_id')->nullable()->constrained('warehouse_kelompok_barang')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');

            $table->string("principal")->nullable();
            $table->integer("harga_principal")->nullable();
            $table->integer("diskon_principal")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_barang_farmasi');
    }
};
