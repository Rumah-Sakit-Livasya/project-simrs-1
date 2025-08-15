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
        Schema::create('farmasi_resep_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('resep_id')->constrained('farmasi_reseps', 'id')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->constrained('warehouse_barang_farmasi')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('satuan_id')->nullable()->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->foreignId('racikan_id')->nullable()->constrained('farmasi_resep_items')->onDelete('cascade');
            $table->enum('tipe', ['obat', 'racikan'])->default('racikan');
            $table->string('signa')->nullable();
            $table->string('instruksi')->nullable();
            $table->string('jam_pemberian')->nullable();
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('embalase');
            $table->integer('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_resep_items');
    }
};
