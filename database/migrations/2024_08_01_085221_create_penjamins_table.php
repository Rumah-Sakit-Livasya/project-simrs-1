<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjaminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjamin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_penjamin_id')->constrained('group_penjamins')->onUpdate('cascade')->onDelete('cascade');
            $table->string("mulai_kerjasama");
            $table->string("akhir_kerjasama")->nullable();
            $table->string("tipe_perusahaan");
            $table->string("kode_perusahaan")->nullable();
            $table->string("nama_perusahaan");
            $table->string("alamat_surat")->nullable();
            $table->string("alamat_email")->nullable();
            $table->string("direktur")->nullable();
            $table->string("nama_kontak")->nullable();
            $table->string("diskon")->default(0);
            $table->string("jabatan")->nullable();
            $table->boolean("termasuk_penjamin")->default(1);
            $table->string("fax_kontak")->nullable();
            $table->string("alamat")->nullable();
            $table->string("alamat_tagihan")->nullable();
            $table->string("telepon_kontak")->nullable();
            $table->string("email_kontak")->nullable();
            $table->string("kota")->nullable();
            $table->boolean("status")->default(1);
            $table->string("kode_pos")->nullable();
            $table->string("jenis_kerjasama");
            $table->string("jenis_kontrak");
            $table->boolean("pasien_otc")->default(0);
            $table->text("keterangan")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjamins');
    }
}
