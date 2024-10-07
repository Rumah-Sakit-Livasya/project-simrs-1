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
            $table->text('keluhan_utama')->nullable();

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
            $table->string('lingkar_kepala')->nullable(); // lingkar kepala
            $table->string('diagnosa_keperawatan')->nullable();
            $table->string('rencana_tindak_lanjut')->nullable();

            //======== Alergi dan Reaksi =========
            $table->string('alergi_obat')->nullable();
            $table->string('ket_alergi_obat')->nullable();
            $table->string('reaksi_alergi_obat')->nullable();
            $table->string('alergi_makanan')->nullable();
            $table->string('ket_alergi_makanan')->nullable();
            $table->string('reaksi_alergi_makanan')->nullable();
            $table->string('alergi_lainnya')->nullable();
            $table->string('ket_alergi_lainnya')->nullable();
            $table->string('reaksi_alergi_lainnya')->nullable();
            $table->boolean('gelang')->default(false);

            //======== Skrining Nyeri =========
            $table->string('skor_nyeri')->nullable();
            $table->string('provokatif')->nullable();
            $table->string('quality')->nullable();
            $table->string('region')->nullable();
            $table->string('time')->nullable();
            $table->string('nyeri')->nullable();
            $table->string('nyeri_hilang')->nullable();

            //======== Skrining Gizi =========
            $table->string('penurunan_bb')->nullable();
            $table->string('asupan_makan')->nullable();
            $table->string('kondisi_khusus1')->nullable(); // Anak usia 1-5 tahun  
            $table->string('kondisi_khusus2')->nullable(); // Lansia > 60 tahun  
            $table->string('kondisi_khusus3')->nullable(); // Penyakit kronis dengan komplikasi  
            $table->string('kondisi_khusus4')->nullable(); // Kanker stadium III/IV  
            $table->string('kondisi_khusus5')->nullable(); // HIV/AIDS  
            $table->string('kondisi_khusus6')->nullable(); // TB  
            $table->string('kondisi_khusus7')->nullable(); // Bedah mayor degestif  
            $table->string('kondisi_khusus8')->nullable(); // Luka bakar > 20%  

            //======== Riwayat Imunisasi dasar =========
            $table->string('imunisasi_dasar1')->nullable(); // BCG  
            $table->string('imunisasi_dasar2')->nullable(); // DPT
            $table->string('imunisasi_dasar3')->nullable(); // Hepatitis B
            $table->string('imunisasi_dasar4')->nullable(); // Polio
            $table->string('imunisasi_dasar5')->nullable(); // Campak

            //======== SKRINING RESIKO JATUH - GET UP & GO =========
            $table->string('resiko_jatuh1')->nullable(); // Tidak seimbang/sempoyongan/limbung
            $table->string('resiko_jatuh2')->nullable(); // Alat bantu: kruk,kursi roda/dibantu
            $table->string('resiko_jatuh3')->nullable(); // Pegang pinggiran meja/kursi/alat bantu untuk duduk

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
