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
        Schema::create('warehouse_stock_opname_item', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('kode_so');
            $table->foreignId("user_id")->constrained("users")->comment("Current logged in user")->cascadeOnDelete();
            $table->foreignId("sog_id")->constrained("warehouse_stock_opname_gudang")->comment("Stock opname header")->cascadeOnDelete();
            $table->foreignId('si_f_id')->nullable()->constrained('stored_barang_farmasi', 'id')->comment('Foreign Key to StoredBarangFarmasi Table');
            $table->foreignId('si_nf_id')->nullable()->constrained('stored_barang_non_farmasi', 'id')->comment('Foreign Key to StoredBarangNonFarmasi Table');
            $table->integer("qty");
            $table->string("keterangan")->nullable();
            $table->enum("status", ["draft", "final"])->default("draft");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_opname_item');
    }
};
