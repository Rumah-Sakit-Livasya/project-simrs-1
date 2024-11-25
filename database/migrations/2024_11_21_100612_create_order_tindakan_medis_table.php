<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_tindakan_medis', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal_tindakan');
            $table->foreignId('user_id')->constrained('users'); // Assuming you have a doctors table
            $table->foreignId('registration_id')->constrained('registrations'); // Assuming you have a doctors table
            $table->foreignId('doctor_id')->constrained('doctors'); // Assuming you have a doctors table
            $table->foreignId('departement_id')->constrained('departements'); // Assuming you have a departements table
            $table->foreignId('tindakan_medis_id')->constrained('tindakan_medis'); // Assuming you have a tindakan_medis table
            $table->string('kelas');
            $table->integer('qty');
            $table->boolean('diskon_dokter')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_tindakan_medis');
    }
};
