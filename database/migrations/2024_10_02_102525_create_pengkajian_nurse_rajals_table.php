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
        Schema::create('pengkajian_nurse_rajal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->string('tgl_masuk')->nullable();
            $table->string('jam_masuk')->nullable();
            $table->string('tgl_dilayani')->nullable();
            $table->string('jam_dilayani')->nullable();
            $table->text('keluhan_utama')->nullable(); // Changed to text

            //======== TTV =========
            $table->string('pr')->nullable(); // nadi
            $table->string('rr')->nullable(); // respirasi
            $table->string('bp')->nullable(); // tensi
            $table->string('temperatur')->nullable(); // suhu
            $table->string('body_height')->nullable(); // tinggi badan
            $table->string('body_weight')->nullable(); // berat badan
            $table->string('bmi')->nullable(); // index masa tubuh
            $table->string('kat_bmi')->nullable(); // kategori IMT
            $table->string('sp02')->nullable(); // sp02
            // $table->string('lingkar_kepala')->nullable(); // lingkar kepala
            $table->text('diagnosa_keperawatan')->nullable(); // Changed to text
            $table->text('rencana_tindak_lanjut')->nullable(); // Changed to text

            //======== Alergi dan Reaksi =========
            $table->text('alergi_obat')->nullable(); // Changed to text
            $table->text('ket_alergi_obat')->nullable(); // Changed to text
            $table->text('reaksi_alergi_obat')->nullable(); // Changed to text
            $table->text('alergi_makanan')->nullable(); // Changed to text
            $table->text('ket_alergi_makanan')->nullable(); // Changed to text
            $table->text('reaksi_alergi_makanan')->nullable(); // Changed to text
            $table->text('alergi_lainnya')->nullable(); // Changed to text
            $table->text('ket_alergi_lainnya')->nullable(); // Changed to text
            $table->boolean('gelang')->default(false);

            //======== Skrining Nyeri =========
            $table->string('skor_nyeri')->nullable();
            $table->text('provokatif')->nullable(); // Changed to text
            $table->text('quality')->nullable(); // Changed to text
            $table->text('region')->nullable(); // Changed to text
            $table->text('time')->nullable(); // Changed to text
            $table->text('nyeri')->nullable(); // Changed to text
            $table->text('nyeri_hilang')->nullable(); // Changed to text

            //======== Skrining Gizi =========
            $table->text('penurunan_bb')->nullable(); // Changed to text
            $table->text('asupan_makan')->nullable(); // Changed to text
            // Other conditions can remain as string if they are short
            $table->json('kondisi_khusus')->nullable(); // Anak usia  1-5 tahun

            //======== Riwayat Imunisasi dasar =========
            $table->json('imunisasi_dasar')->nullable(); // BCG

            //======== SKRINING RESIKO JATUH - GET UP & GO =========
            $table->string('resiko_jatuh')->nullable(); // Tidak seimbang/sempoyongan/limbung

            // //======== RIWAYAT PSIKOSOSIAL, SPIRITUAL & KEPERCAYAAN =========
            // $table->text('status_psikologis')->nullable(); // Luka bakar > 20%
            // $table->text('status_spiritual')->nullable(); // Luka bakar > 20%
            // $table->text('masalah_prilaku')->nullable(); // Luka bakar > 20%
            // $table->text('hub_dengan_keluarga')->nullable(); // Luka bakar > 20%
            // $table->text('tempat_tinggal')->nullable(); // Luka bakar > 20%
            // $table->text('kerabat_dihub')->nullable(); // Luka bakar > 20%
            // $table->text('no_kontak_kerabat')->nullable(); // Luka bakar > 20%
            // $table->text('status_perkawinan')->nullable(); // Luka bakar > 20%
            // $table->text('pekerjaan')->nullable(); // Luka bakar > 20%
            // $table->text('penghasilan')->nullable(); // Luka bakar > 20%
            // $table->text('pendidikan')->nullable(); // Luka bakar > 20%

            // //======== KEBUTUHAN EDUKASI =========
            // $table->text('hambatan_belajar1')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar2')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar3')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar4')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar5')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar6')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar7')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar8')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_belajar9')->nullable(); // Luka bakar > 20%
            // $table->text('hambatan_lainnya')->nullable(); // Luka bakar > 20%
            // $table->text('kebutuhan_penerjemah')->nullable(); // Luka bakar > 20%

            // $table->text('kebuthan_pembelajaran1')->nullable(); // Luka bakar > 20%
            // $table->text('kebuthan_pembelajaran2')->nullable(); // Luka bakar > 20%
            // $table->text('kebuthan_pembelajaran3')->nullable(); // Luka bakar > 20%
            // $table->text('kebuthan_pembelajaran4')->nullable(); // Luka bakar > 20%
            // $table->text('kebuthan_pembelajaran5')->nullable(); // Luka bakar > 20%
            // $table->text('kebuthan_pembelajaran6')->nullable(); // Luka bakar > 20%
            // $table->text('kebuthan_pembelajaran7')->nullable(); // Luka bakar > 20%

            // $table->text('pembelajaran_lainnya')->nullable(); // Luka bakar > 20%

            // //======== Assesment fungsional =========
            // $table->text('sensorik_penglihatan')->nullable(); // Luka bakar > 20%
            // $table->text('sensorik_penciuman')->nullable(); // Luka bakar > 20%
            // $table->text('sensorik_pendengaran')->nullable(); // Luka bakar > 20%

            // //======== Kognitif =========
            // $table->text('kognitif')->nullable(); // Luka bakar > 20%

            // //======== Motorik =========
            // $table->text('motorik_aktifitas')->nullable(); // Luka bakar > 20%
            // $table->text('motorik_berjalan')->nullable(); // Luka bakar > 20%

            $table->timestamps(); // created_at dan updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengkajian_nurse_rajal');
    }
};
