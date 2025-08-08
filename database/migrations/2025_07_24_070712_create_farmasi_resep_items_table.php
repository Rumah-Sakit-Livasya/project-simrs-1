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
            $table->foreignId('barang_id')->constrained('warehouse_barang_farmasi')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->string('signa');
            $table->string('instruksi');
            $table->string('jam_pemberian')->nullable();
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('embalase');
            $table->integer('subtotal');
            $table->boolean('dijamin');
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
