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
        Schema::create('pertanggungjawaban_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanggungjawaban_id')->constrained('pertanggungjawabans')->onDelete('cascade');
            $table->enum('tipe', ['pj', 'reimburse']); // Tipe detail, PJ atau Reimburse
            $table->string('tipe_transaksi')->nullable(); // Ganti jadi foreignId jika ada tabel master
            $table->string('cost_center')->nullable();    // Ganti jadi foreignId jika ada tabel master
            $table->text('keterangan');
            $table->decimal('nominal', 15, 2);
            $table->string('attachment')->nullable(); // Untuk nota/bukti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanggungjawaban_details');
    }
};
