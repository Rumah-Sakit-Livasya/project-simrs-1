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
            // ================ Transfer Pasien Antar Ruangan ================
            $table->string('tgl')->nullable();
            $table->string('jam')->nullable();
            $table->string('tgl_masuk_pasien')->nullable();
            $table->string('jam_masuk_pasien')->nullable();
            $table->string('asesmen')->nullable();
            $table->string('masalah_keperawatan')->nullable();
            $table->string('dokter')->nullable();
            $table->string('dokter2')->nullable();
            $table->string('dokter3')->nullable();

            // ----------------- Asal Ruangan : ------------------
            $table->string('ruangan_asal')->nullable();
            $table->string('kelas_asal')->nullable();
            $table->string('ruangan_pindah')->nullable();
            $table->string('kelas_pindah')->nullable();
            $table->string('tiba_diruangan')->nullable();

            // ----------------- Asal Ruangan : ------------------
            $table->text('keluhan_utama')->nullable();
            $table->string('tiba_diruangan')->nullable();
            $table->string('keadaan_umum')->nullable();
            $table->string('ket_gcs')->nullable();

            // ----------------- TTV : ------------------
            $table->string('td')->nullable();
            $table->string('nd')->nullable();
            $table->string('rr')->nullable();
            $table->string('sb')->nullable();
            $table->string('bb')->nullable();
            $table->string('tb')->nullable();
            $table->string('spo2')->nullable();
            $table->string('status_nyeri')->nullable();

            // ----------------- Alasan pemindahan pasien: ------------------
            $table->string('kondisi_pasien')->nullable();
            $table->string('tindakan')->nullable();
            $table->string('app_lainnya_text')->nullable();
            $table->string('kesadaran')->nullable();

            // ----------------- Alasan pemindahan pasien: ------------------
            $table->string('rj_tidak_beresiko')->nullable();
            $table->string('mpp_kuro')->nullable();
            $table->string('kti_kontak')->nullable();
            $table->boolean('mpi')->nullable();
            $table->boolean('ap')->nullable();
            $table->string('ap_nama')->nullable();
            $table->string('ap_hubungan')->nullable();

            // ----------------- Peralatan yang menyertai saat pemindahan: ------------------
            $table->string('pmp_kuro')->nullable();
            $table->text('pmp_text')->nullable();
            $table->string('pmp_cateter_urine')->nullable();
            $table->string('pmp_ngt')->nullable();
            $table->string('sfp_mandiri')->nullable();
            $table->text('alasan_pdh_temuan_anamesis')->nullable();
            $table->text('pemeriksaan_penunjang')->nullable();
            $table->text('intervensi_tindakan')->nullable();
            $table->text('diet')->nullable();

            // ----------------- PEMBERIAN THERAPI SEBELUM PINDAH: ------------------
            $table->string('ptsp_infus')->nullable();
            $table->text('ptsp_infus_text')->nullable();
            $table->string('ptsp_infus_tetesan')->nullable();

            // ----------------- Terapi Dan Tindakan Yang Dilakukan: ------------------
            $table->string('resep1')->nullable();
            $table->string('jam_pemberian1')->nullable();
            $table->string('resep2')->nullable();
            $table->string('jam_pemberian2')->nullable();
            $table->string('resep3')->nullable();
            $table->string('jam_pemberian3')->nullable();
            $table->string('resep4')->nullable();
            $table->string('jam_pemberian4')->nullable();
            $table->string('resep5')->nullable();
            $table->string('jam_pemberian5')->nullable();
            $table->string('resep6')->nullable();
            $table->string('jam_pemberian6')->nullable();
            $table->string('resep7')->nullable();
            $table->string('jam_pemberian7')->nullable();
            $table->string('resep8')->nullable();
            $table->string('jam_pemberian8')->nullable();
            $table->string('resep9')->nullable();
            $table->string('jam_pemberian9')->nullable();
            $table->string('resep10')->nullable();
            $table->string('jam_pemberian10')->nullable();
            $table->string('data_ttd1')->nullable();
            $table->string('nama_perawat_pengirim')->nullable();
            $table->string('data_ttd2')->nullable();
            $table->string('nama_perawat_penerima')->nullable();

            // ---------------- pasien kembali keruang semula pasca tindakan/ prosedur ----------------
            $table->string('pasien_kelmbali')->nullable();
            $table->string('keadaan_umum2')->nullable();
            $table->string('td2')->nullable();
            $table->string('nd2')->nullable();
            $table->string('rr2')->nullable();
            $table->string('sb2')->nullable();
            $table->string('data_ttd3')->nullable();
            $table->string('nama_perawat_pengirim2')->nullable();
            $table->string('data_ttd4')->nullable();
            $table->string('nama_perawat_penerima2')->nullable();
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
