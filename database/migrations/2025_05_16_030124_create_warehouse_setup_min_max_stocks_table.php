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
        Schema::create('warehouse_setup_min_max_stock', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('barang_f_id')->nullable()->comment("barang farmasi")->constrained('warehouse_barang_farmasi')->onDelete('cascade');
            $table->foreignId('barang_nf_id')->nullable()->comment("barang non farmasi")->constrained('warehouse_barang_non_farmasi')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang')->onDelete('cascade');
            $table->integer("min");
            $table->integer("max");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_setup_min_max_stock');
    }
};
