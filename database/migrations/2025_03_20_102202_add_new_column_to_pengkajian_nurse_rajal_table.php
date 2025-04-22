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
            // Cek dan hapus kolom kondisi_khusus1 hingga kondisi_khusus8
            // for ($i = 1; $i <= 8; $i++) {
            //     if (Schema::hasColumn('pengkajian_nurse_rajal', "kondisi_khusus$i")) {
            //         $table->dropColumn("kondisi_khusus$i");
            //     }
            // }

            // Cek dan hapus kolom imunisasi_dasar1 hingga imunisasi_dasar5
            // for ($i = 1; $i <= 5; $i++) {
            //     if (Schema::hasColumn('pengkajian_nurse_rajal', "imunisasi_dasar$i")) {
            //         $table->dropColumn("imunisasi_dasar$i");
            //     }
            // }

            // Cek dan hapus kolom resiko_jatuh1 hingga resiko_jatuh3
            // for ($i = 1; $i <= 3; $i++) {
            //     if (Schema::hasColumn('pengkajian_nurse_rajal', "resiko_jatuh$i")) {
            //         $table->dropColumn("resiko_jatuh$i");
            //     }
            // }

            // Tambahkan kolom baru
            // $table->json('kondisi_khusus')->nullable();
            // $table->json('imunisasi_dasar')->nullable();
            // $table->json('resiko_jatuh')->nullable();

            // $table->string('lingkar_kepala', 20)->nullable()->after('sp02');
            // $table->string('ket_alergi_obat', 20)->nullable()->after('alergi_obat');
            // $table->string('ket_alergi_makanan', 20)->nullable()->after('alergi_makanan');
            // $table->string('ket_alergi_lainnya', 20)->nullable()->after('alergi_lainnya');

            $table->string('hasil_resiko_jatuh')->nullable();
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

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengkajian_nurse_rajal', function (Blueprint $table) {
            // Tambahkan kembali kolom kondisi_khusus1 hingga kondisi_khusus8
            // for ($i = 1; $i <= 8; $i++) {
            //     if (!Schema::hasColumn('pengkajian_nurse_rajal', "kondisi_khusus$i")) {
            //         $table->string("kondisi_khusus$i")->nullable();
            //     }
            // }

            // Tambahkan kembali kolom imunisasi_dasar1 hingga imunisasi_dasar5
            // for ($i = 1; $i <= 5; $i++) {
            //     if (!Schema::hasColumn('pengkajian_nurse_rajal', "imunisasi_dasar$i")) {
            //         $table->string("imunisasi_dasar$i")->nullable();
            //     }
            // }

            // Tambahkan kembali kolom resiko_jatuh1 hingga resiko_jatuh3
            // for ($i = 1; $i <= 3; $i++) {
            //     if (!Schema::hasColumn('pengkajian_nurse_rajal', "resiko_jatuh$i")) {
            //         $table->string("resiko_jatuh$i")->nullable();
            //     }
            // }

            // Hapus kolom baru yang ditambahkan
            // $table->dropColumn('kondisi_khusus');
            // $table->dropColumn('imunisasi_dasar');
            // $table->dropColumn('resiko_jatuh');

            // $table->dropColumn('lingkar_kepala');
            // $table->dropColumn('ket_alergi_obat');
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

            // Hapus foreign key sebelum menghapus kolom
            if (Schema::hasColumn('pengkajian_nurse_rajal', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('pengkajian_nurse_rajal', 'modified_by')) {
                $table->dropForeign(['modified_by']);
                $table->dropColumn('modified_by');
            }
        });
    }
};
