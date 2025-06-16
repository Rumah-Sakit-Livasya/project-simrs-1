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
        Schema::create('warehouse_retur_barang', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date("tanggal_retur");
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('warehouse_supplier', 'id')->comment('Foreign Key to WarehouseSupplier Table');
            $table->string("keterangan")->nullable();
            $table->String("kode_retur");
            $table->integer("ppn")->default(0);
            $table->integer("ppn_nominal")->default(0);
            $table->integer("nominal");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_retur_barang');
    }
};
