<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_supplier', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->enum('kategori', ["FARMASI", "UMUM"]);
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('no_rek')->nullable();
            $table->string('bank')->nullable();
            $table->enum('top', ["COD", "7HARI", "14HARI", "21HARI", "24HARI", "30HARI", "37HARI", "40HARI", "45HARI"])->nullable();
            $table->enum('tipe_top', ["SETELAH_TUKAR_FAKTUR", "SETELAH_TERIMA_BARANG"])->default("SETELAH_TUKAR_FAKTUR");
            $table->integer('ppn');
            $table->boolean('aktif')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_supplier');
    }
};
