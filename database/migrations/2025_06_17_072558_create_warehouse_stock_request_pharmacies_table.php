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
        Schema::create('warehouse_stock_request_pharmacy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes(); // Add soft delete column
            $table->date("tanggal_sr");
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId('asal_gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->foreignId('tujuan_gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->string("kode_sr");
            $table->string("keterangan")->nullable();
            $table->enum("tipe", ["normal", "urgent"])->default("normal");
            $table->enum("status", ["draft", "final"])->default("draft");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_request_pharmacy');
    }
};
