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
        Schema::create('farmasi_resep_harian_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('rh_id')->constrained('farmasi_resep_harians')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('barang_id')->constrained('warehouse_barang_farmasi')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('signa')->nullable();
            $table->integer('qty_perhari');
            $table->integer('qty_hari');
            $table->integer('qty_diberi')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_resep_harian_items');
    }
};
