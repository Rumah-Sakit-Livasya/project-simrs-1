<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farmasi_telaah_reseps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('resep_id')->constrained('farmasi_reseps', 'id')->onDelete('cascade');
            
            $table->boolean("kejelasan_tulisan")->default(false);
            $table->boolean("benar_pasien")->default(false);
            $table->boolean("benar_nama_obat")->default(false);
            $table->boolean("benar_dosis")->default(false);
            $table->boolean("benar_waktu_dan_frekeunsi_pemberian")->default(false);
            $table->boolean("benar_rute_dan_cara_pemberian")->default(false);
            $table->boolean("ada_alergi_dengan_obat_yang_diresepkan")->default(false);
            $table->boolean("ada_duplikat_obat")->default(false);
            $table->boolean("interaksi_obat_yang_mungkin_terjadi")->default(false);
            $table->boolean("hal_lain_yang_mungkin_terjadi")->default(false);
            $table->boolean("hal_lain_yang_merupakan_masalah_dengan_obat")->default(false);

            $table->text("perubahan_resep_tertulis_1")->nullable();
            $table->text("perubahan_resep_menjadi_1")->nullable();
            $table->text("perubahan_resep_petugas_1")->nullable();
            $table->text("perubahan_resep_disetujui_1")->nullable();

            $table->text("perubahan_resep_tertulis_2")->nullable();
            $table->text("perubahan_resep_menjadi_2")->nullable();
            $table->text("perubahan_resep_petugas_2")->nullable();
            $table->text("perubahan_resep_disetujui_2")->nullable();

            $table->text("perubahan_resep_tertulis_3")->nullable();
            $table->text("perubahan_resep_menjadi_3")->nullable();
            $table->text("perubahan_resep_petugas_3")->nullable();
            $table->text("perubahan_resep_disetujui_3")->nullable();

            $table->text("alamat_no_telp_pasien")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_telaah_reseps');
    }
};
