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

        Schema::create('transfer_pasien_antar_ruangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();

            // ================ Transfer Pasien Antar Ruangan ================
            $table->string('tgl', 50)->nullable();
            $table->string('jam', 50)->nullable();
            $table->string('tgl_masuk_pasien', 50)->nullable();
            $table->string('jam_masuk_pasien', 50)->nullable();
            $table->text('asesmen')->nullable();
            $table->text('masalah_keperawatan')->nullable();
            $table->string('dokter', 100)->nullable();
            $table->string('dokter2', 100)->nullable();
            $table->string('dokter3', 100)->nullable();

            // ----------------- Asal Ruangan : ------------------
            $table->string('ruangan_asal', 100)->nullable();
            $table->string('kelas_asal', 50)->nullable();
            $table->string('ruangan_pindah', 100)->nullable();
            $table->string('kelas_pindah', 50)->nullable();

            // ----------------- Asal Ruangan : ------------------
            $table->text('keluhan_utama')->nullable();
            $table->string('tiba_diruangan', 100)->nullable();
            $table->string('keadaan_umum', 100)->nullable();
            $table->string('ket_gcs', 100)->nullable();

            // ----------------- TTV : ------------------
            $table->string('td', 50)->nullable();
            $table->string('nd', 50)->nullable();
            $table->string('rr', 50)->nullable();
            $table->string('sb', 50)->nullable();
            $table->string('bb', 50)->nullable();
            $table->string('tb', 50)->nullable();
            $table->string('spo2', 50)->nullable();
            $table->string('status_nyeri', 50)->nullable();

            // ----------------- Alasan pemindahan pasien: ------------------
            $table->string('tindakan')->nullable();
            $table->text('ket_lainnya')->nullable();
            $table->string('app_lainnya')->nullable();
            $table->text('app_lainnya_text')->nullable();
            $table->string('kesadaran')->nullable();

            // Metode Pemindahan Pasien
            $table->string('mpp')->nullable();

            // Risiko Jatuh
            $table->string('rj')->nullable();

            // Kewaspadaam Transmisi/Infeksi
            $table->string('kti')->nullable();

            // Kewaspadaam Transmisi/Infeksi
            $table->boolean('mpi')->nullable();


            // ----------------- Alasan pemindahan pasien: ------------------
            $table->boolean('ap')->nullable();
            $table->string('ap_nama')->nullable();
            $table->string('ap_hubungan')->nullable();
            $table->text('alasan_pdh_temuan_anamesis')->nullable();

            // Status Fungsional Pasien
            $table->string('sfp')->nullable();

            // ----------------- Peralatan yang menyertai saat pemindahan: ------------------
            $table->string('pmp_kuro')->nullable();
            $table->text('pmp_text')->nullable();
            $table->string('pmp_cateter_urine')->nullable();
            $table->string('pmp_ngt')->nullable();
            $table->text('pemeriksaan_penunjang')->nullable();
            $table->text('intervensi_tindakan')->nullable();
            $table->text('diet')->nullable();

            // ----------------- PEMBERIAN THERAPI SEBELUM PINDAH: ------------------
            $table->string('ptsp_infus')->nullable();
            $table->text('ptsp_infus_text')->nullable();
            $table->string('ptsp_infus_tetesan')->nullable();

            // ----------------- Terapi Dan Tindakan Yang Dilakukan: ------------------
            $table->text('resep1')->nullable();
            $table->text('jam_pemberian1')->nullable();
            $table->text('resep2')->nullable();
            $table->text('jam_pemberian2')->nullable();
            $table->text('resep3')->nullable();
            $table->text('jam_pemberian3')->nullable();
            $table->text('resep4')->nullable();
            $table->text('jam_pemberian4')->nullable();
            $table->text('resep5')->nullable();
            $table->text('jam_pemberian5')->nullable();
            $table->text('resep6')->nullable();
            $table->text('jam_pemberian6')->nullable();
            $table->text('resep7')->nullable();
            $table->text('jam_pemberian7')->nullable();
            $table->text('resep8')->nullable();
            $table->text('jam_pemberian8')->nullable();
            $table->text('resep9')->nullable();
            $table->text('jam_pemberian9')->nullable();
            $table->text('resep10')->nullable();
            $table->text('jam_pemberian10')->nullable();

            $table->string('data_ttd1')->nullable();
            $table->string('nama_perawat_pengirim')->nullable();
            $table->string('data_ttd2')->nullable();
            $table->string('nama_perawat_penerima')->nullable();

            // pasien kembali keruang semula pasca tindakan/ prosedur
            $table->string('pasien_kelmbali')->nullable();
            $table->string('keadaan_umum_after')->nullable();
            $table->string('td_after')->nullable();
            $table->string('nd_after')->nullable();
            $table->string('rr_after')->nullable();
            $table->string('sb_after')->nullable();
            $table->string('rj_after')->nullable();
            $table->text('diet_after')->nullable();

            $table->string('data_ttd3')->nullable();
            $table->string('nama_perawat_pengirim_after')->nullable();
            $table->string('data_ttd4')->nullable();
            $table->string('nama_perawat_penerima_after')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_pasien_antar_ruangan');
    }
};
