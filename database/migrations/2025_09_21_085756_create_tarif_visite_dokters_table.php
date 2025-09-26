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
        Schema::create('tarif_visite_dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat')->onDelete('cascade');
            $table->bigInteger('share_rs')->default(0);
            $table->bigInteger('share_dr')->default(0);
            $table->bigInteger('prasarana')->default(0);
            $table->bigInteger('total')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_visite_dokters');
    }
};
