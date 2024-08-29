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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_barang_id');
            $table->foreignId('category_barang_id');
            $table->foreignId('company_id');
            $table->foreignId('room_id');
            $table->boolean('barang_ruang')->default(false);
            $table->string('custom_name')->nullable();
            $table->string('condition');
            $table->string('bidding_year');
            $table->string('urutan_barang')->default(0);
            $table->string('item_code')->default(false);
            $table->string('merk')->nullable();
            $table->string('pinjam')->default(false);
            $table->string('ruang_pinjam')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
