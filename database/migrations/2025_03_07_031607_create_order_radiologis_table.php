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
        Schema::create('order_radiologi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('dokter_radiologi_id')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->date('order_date');
            $table->dateTime('inspection_date')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('no_order');
            $table->enum('tipe_order', ['normal', 'cito'])->default('normal');
            $table->string('tipe_pasien');
            $table->string('diagnosa_klinis');
            $table->tinyInteger('status_isi_hasil')->default(0);
            $table->boolean('status_billed')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_radiologi');
    }
};
