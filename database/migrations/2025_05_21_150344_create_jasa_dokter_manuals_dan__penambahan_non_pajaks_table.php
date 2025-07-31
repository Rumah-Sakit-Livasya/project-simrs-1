<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jasa_dokter_manuals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembayaran_jasa_dokter_id');
            $table->string('keterangan')->nullable();
            $table->string('akun')->nullable();
            $table->string('cost_revenue')->nullable();
            $table->decimal('jasa_dokter', 15, 2)->default(0);
            $table->decimal('jkp_tambahan', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('pembayaran_jasa_dokter_id')
                ->references('id')
                ->on('pembayaran_jasa_dokter')
                ->onDelete('cascade');
        });

        Schema::create('penambahan_non_pajaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembayaran_jasa_dokter_id');
            $table->string('keterangan')->nullable();
            $table->string('akun')->nullable();
            $table->string('cost_revenue')->nullable();
            $table->decimal('jasa_dokter', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('pembayaran_jasa_dokter_id')
                ->references('id')
                ->on('pembayaran_jasa_dokter')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penambahan_non_pajaks');
        Schema::dropIfExists('jasa_dokter_manuals');
    }
};
