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
        Schema::create('pertanggungjawabans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pj')->unique();
            $table->date('tanggal_pj');

            // Relasi ke pencairan yang di-PJ-kan (SANGAT PENTING)
            $table->foreignId('pencairan_id')->constrained('pencairans')->onDelete('restrict');


            $table->decimal('total_pj', 15, 2)->comment('Total nominal dari semua detail PJ');
            $table->decimal('selisih', 15, 2)->comment('Nominal Pencairan - Total PJ. Positif = sisa, Negatif = reimburse');
            $table->text('keterangan')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreignId('user_entry_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanggungjawabans');
    }

    /**
     * Reverse the migrations.
     */
};
