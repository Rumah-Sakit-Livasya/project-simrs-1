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
        Schema::create('peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50);
            $table->string('nama', 100);
            $table->string('satuan_pakai', 50);
            $table->bigInteger('coa_pendapatan_rajal')->nullable();
            $table->bigInteger('coa_biaya_rajal')->nullable();
            $table->bigInteger('coa_pendapatan_ranap')->nullable();
            $table->bigInteger('coa_biaya_ranap')->nullable();
            $table->boolean('is_req_dokter')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peralatan');
    }
};
