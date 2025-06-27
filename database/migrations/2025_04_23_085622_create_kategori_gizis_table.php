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
        Schema::create('kategori_gizi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("nama");
            $table->boolean("aktif");
            $table->string("coa_pendapatan");
            $table->string("coa_biaya");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_gizi');
    }
};
