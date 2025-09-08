<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_persalinan', function (Blueprint $table) {
            $table->id();

            // [DIGABUNGKAN] Relasi ke registrasi pasien
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');

            // [DITAMBAHKAN] Relasi ke user yang menginput data
            $table->foreignId('user_entry_id')->nullable()->constrained('users')->onDelete('set null');

            $table->dateTime('tgl_order')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('tgl_persalinan');

            // Relasi ke tenaga medis
            $table->foreignId('dokter_bidan_operator_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('dokter_resusitator_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('dokter_anestesi_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('dokter_umum_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('asisten_operator_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('asisten_anestesi_id')->nullable()->constrained('doctors')->onDelete('set null');

            // Relasi ke master data
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_persalinan')->onDelete('cascade');
            $table->foreignId('tipe_penggunaan_id')->nullable()->constrained('tipe_persalinan')->onDelete('set null');

            // Status
            $table->boolean('melahirkan_bayi')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });

        // Pivot tabel untuk tindakan
        // Schema::create('order_persalinan_detail', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('order_persalinan_id')->constrained('order_persalinan')->onDelete('cascade');
        //     $table->foreignId('persalinan_id')->constrained('persalinan')->onDelete('cascade'); // pilihan tindakan
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Schema::dropIfExists('order_persalinan_detail');
        Schema::dropIfExists('order_persalinan');
    }
};
