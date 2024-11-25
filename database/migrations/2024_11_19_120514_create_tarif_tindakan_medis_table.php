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
        Schema::create('tarif_tindakan_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tindakan_medis_id')->constrained('tindakan_medis')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('group_penjamin_id');
            $table->bigInteger('kelas_rawat_id');
            $table->bigInteger('share_dr')->default(0); // Use integer instead of string for default
            $table->bigInteger('share_rs')->default(0);
            $table->bigInteger('prasarana')->default(0);
            $table->bigInteger('bhp')->default(0);
            $table->bigInteger('total')->default(0);
            $table->softDeletes(); // Enables soft deletes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_tindakan_medis');
    }
};
