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
        Schema::table('pengkajian_nurse_rajal', function (Blueprint $table) {
            $table->dropColumn('kondisi_khusus1');
            $table->dropColumn('kondisi_khusus2');
            $table->dropColumn('kondisi_khusus3');
            $table->dropColumn('kondisi_khusus4');
            $table->dropColumn('kondisi_khusus5');
            $table->dropColumn('kondisi_khusus6');
            $table->dropColumn('kondisi_khusus7');
            $table->dropColumn('kondisi_khusus8');

            $table->dropColumn('imunisasi_dasar1');
            $table->dropColumn('imunisasi_dasar2');
            $table->dropColumn('imunisasi_dasar3');
            $table->dropColumn('imunisasi_dasar4');
            $table->dropColumn('imunisasi_dasar5');

            $table->dropColumn('resiko_jatuh1');
            $table->dropColumn('resiko_jatuh2');
            $table->dropColumn('resiko_jatuh3');

            $table->json('kondisi_khusus')->nullable();
            $table->json('imunisasi_dasar')->nullable();
            $table->json('resiko_jatuh')->nullable();

            $table->string('lingkar_kepala', 20)->nullable()->after('sp02');
            $table->string('ket_alergi_obat', 20)->nullable()->after('alergi_obat');
            $table->string('ket_alergi_makanan', 20)->nullable()->after('alergi_makanan');
            $table->string('ket_alergi_lainnya', 20)->nullable()->after('alergi_lainnya');

            $table->string('hasil_resiko_jatuh')->nullable()->after('resiko_jatuh');

            $table->string('status_psikologis')->nullable()->after('hasil_resiko_jatuh');
            $table->string('status_spiritual')->nullable()->after('status_psikologis');
            $table->string('masalah_prilaku')->nullable()->after('status_spiritual');
            $table->string('kekerasan_dialami')->nullable()->after('masalah_prilaku');
            $table->string('hub_dengan_keluarga')->nullable()->after('kekerasan_dialami');
            $table->string('tempat_tinggal')->nullable()->after('hub_dengan_keluarga');
            $table->string('kerabat_dihub')->nullable()->after('tempat_tinggal');
            $table->string('no_kontak_kerabat')->nullable()->after('kerabat_dihub');
            $table->string('penghasilan')->nullable()->after('no_kontak_kerabat');

            $table->json('hambatan_belajar')->nullable()->after('penghasilan');
            $table->string('hambatan_lainnya')->nullable()->after('hambatan_belajar');
            $table->string('kebutuhan_penerjemah')->nullable()->after('hambatan_lainnya');
            $table->json('kebutuhan_pembelajaran')->nullable()->after('kebutuhan_penerjemah');
            $table->string('pembelajaran_lainnya')->nullable()->after('kebutuhan_pembelajaran');
            $table->json('sensorik')->nullable()->after('pembelajaran_lainnya');
            $table->string('kognitif')->nullable()->after('sensorik');
            $table->json('motorik')->nullable()->after('kognitif');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->foreign('modified_by')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengkajian_nurse_rajal', function (Blueprint $table) {
            // Menambahkan kembali kolom yang dihapus
            $table->string('kondisi_khusus1')->nullable();
            $table->string('kondisi_khusus2')->nullable();
            $table->string('kondisi_khusus3')->nullable();
            $table->string('kondisi_khusus4')->nullable();
            $table->string('kondisi_khusus5')->nullable();
            $table->string('kondisi_khusus6')->nullable();
            $table->string('kondisi_khusus7')->nullable();
            $table->string('kondisi_khusus8')->nullable();

            $table->string('imunisasi_dasar1')->nullable();
            $table->string('imunisasi_dasar2')->nullable();
            $table->string('imunisasi_dasar3')->nullable();
            $table->string('imunisasi_dasar4')->nullable();
            $table->string('imunisasi_dasar5')->nullable();

            $table->string('resiko_jatuh1')->nullable();
            $table->string('resiko_jatuh2')->nullable();
            $table->string('resiko_jatuh3')->nullable();

            // Menghapus kolom baru yang ditambahkan
            $table->dropColumn('kondisi_khusus');
            $table->dropColumn('imunisasi_dasar');
            $table->dropColumn('resiko_jatuh');

            $table->dropColumn('lingkar_kepala');
            $table->dropColumn('ket_alergi_obat');
            $table->dropColumn('ket_alergi_makanan');
            $table->dropColumn('ket_alergi_lainnya');

            $table->dropColumn('hasil_resiko_jatuh');

            $table->dropColumn('status_psikologis');
            $table->dropColumn('status_spiritual');
            $table->dropColumn('masalah_prilaku');
            $table->dropColumn('kekerasan_dialami');
            $table->dropColumn('hub_dengan_keluarga');
            $table->dropColumn('tempat_tinggal');
            $table->dropColumn('kerabat_dihub');
            $table->dropColumn('no_kontak_kerabat');
            $table->dropColumn('penghasilan');

            $table->dropColumn('hambatan_belajar');
            $table->dropColumn('hambatan_lainnya');
            $table->dropColumn('kebutuhan_penerjemah');
            $table->dropColumn('kebutuhan_pembelajaran');
            $table->dropColumn('pembelajaran_lainnya');
            $table->dropColumn('sensorik');
            $table->dropColumn('kognitif');
            $table->dropColumn('motorik');
            $table->dropForeign(['created_by']);
            $table->dropForeign(['modified_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });
    }
};
