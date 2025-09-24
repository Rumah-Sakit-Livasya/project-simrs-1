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
        Schema::create('order_gizi', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('registration_id')->constrained("registrations")->onDelete("cascade");
            $table->foreignId('kategori_id')->constrained(table: 'kategori_gizi')->onDelete('cascade');
            $table->string('nama_pemesan');
            $table->enum('untuk', ['pasien', 'keluarga'])->default('pasien');
            $table->dateTime('tanggal_order');
            $table->string('waktu_makan')->nullable();
            $table->boolean('ditagihkan')->default(true);
            $table->boolean('digabung')->default(true);
            $table->integer('total_harga');
            $table->boolean('status_payment')->default(false);
            $table->boolean('status_order')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_gizi');
    }
};
