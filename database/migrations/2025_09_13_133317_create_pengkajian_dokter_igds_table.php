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
        Schema::create('pengkajian_dokter_igd', function (Blueprint $table) {
            $table->id();
            // Kunci Asing ke tabel registrasi pasien
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');

            // ======================================================================================================
            // ASESMEN AWAL MEDIS
            // ======================================================================================================
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit_sekarang')->nullable();
            $table->text('riwayat_penyakit_dahulu')->nullable();
            $table->string('riwayat_alergi')->nullable(); // Menyimpan 'Tidak' atau 'Ya'
            $table->text('riwayat_alergi_text')->nullable();

            // ======================================================================================================
            // PEMERIKSAAN FISIK
            // ======================================================================================================
            $table->string('keadaan_umum')->nullable();
            $table->tinyInteger('gcse')->unsigned()->nullable();
            $table->tinyInteger('gcsm')->unsigned()->nullable();
            $table->tinyInteger('gcsv')->unsigned()->nullable();
            $table->tinyInteger('gcstotal')->unsigned()->nullable();
            $table->string('tingkat_kesadaran')->nullable();

            // Tanda Vital
            $table->decimal('bb_triage', 5, 2)->nullable(); // e.g., 150.75 kg
            $table->decimal('tb_triage', 5, 2)->nullable(); // e.g., 170.50 cm
            $table->string('td')->nullable(); // e.g., '120/80'
            $table->smallInteger('pr_triage')->unsigned()->nullable(); // Nadi
            $table->smallInteger('rr_triage')->unsigned()->nullable(); // Respirasi
            $table->decimal('sb', 4, 1)->nullable(); // Suhu, e.g., 37.5
            $table->smallInteger('dokterSPO2')->unsigned()->nullable();

            // ======================================================================================================
            // STATUS GENERALIS & LOKALIS
            // ======================================================================================================
            $table->json('status_generalis')->nullable(); // Menyimpan data checkbox dan text sebagai JSON
            $table->text('status_lokalis')->nullable();

            // ======================================================================================================
            // PEMERIKSAAN PENUNJANG & DIAGNOSA
            // ======================================================================================================
            $table->json('pemeriksaan_penunjang')->nullable(); // Menyimpan data checkbox dan text sebagai JSON
            $table->text('diagnosa_kerja')->nullable();
            $table->text('diagnosa_banding')->nullable();

            // ======================================================================================================
            // TERAPI ATAU TINDAKAN
            // ======================================================================================================
            $table->time('jam_tindakan')->nullable();
            $table->text('terapi_tindakan')->nullable();
            $table->text('diberikan_oleh')->nullable();

            // ======================================================================================================
            // KESIMPULAN AKHIR & TINDAK LANJUT
            // ======================================================================================================
            $table->string('kondisi_pulang')->nullable();
            $table->time('jam_meninggal')->nullable();

            // Tanda Vital Saat Pulang
            $table->string('td_pulang')->nullable();
            $table->smallInteger('nadi_pulang')->unsigned()->nullable();
            $table->smallInteger('rr_pulang')->unsigned()->nullable();
            $table->decimal('sb_pulang', 4, 1)->nullable();

            $table->text('terapi_pulang')->nullable();
            $table->json('tindak_lanjut')->nullable(); // Menyimpan pilihan checkbox sebagai array JSON
            $table->string('tindak_lanjut_rujuk_ke_text')->nullable();
            $table->text('alasan_rujuk')->nullable();

            // ======================================================================================================
            // EDUKASI
            // ======================================================================================================
            $table->json('edukasi_penerima')->nullable(); // Menyimpan 'Pasien' dan/atau 'Keluarga'
            $table->boolean('edukasi_tidak_dapat_diberikan')->default(false);
            $table->text('edukasi_alasan')->nullable();

            // ======================================================================================================
            // METADATA & TANDA TANGAN
            // ======================================================================================================
            $table->text('signature')->nullable(); // Menyimpan path atau data base64 tanda tangan
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengkajian_dokter_igd');
    }
};
