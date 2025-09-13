<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bpjs_lpks', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke tabel registrations
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');

            // Contoh kolom data LPK
            $table->string('no_lpk')->unique();
            $table->date('tgl_lpk');
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->string('status_klaim')->default('Diajukan'); // Cth: Diajukan, Diverifikasi, Dibayar

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bpjs_lpks');
    }
};
