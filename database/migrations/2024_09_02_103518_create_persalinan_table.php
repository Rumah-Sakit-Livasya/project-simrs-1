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
        Schema::create('persalinan', function (Blueprint $table) {
            $table->bigIncrements('id'); // AUTO_INCREMENT primary key
            $table->string('tipe', 50);
            $table->string('kode', 50);
            $table->string('nama_persalinan', 100);
            $table->string('nama_billing', 100);
            $table->softDeletes(); // deleted_at nullable
            $table->timestamps(); // created_at & updated_at nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persalinan');
    }
};
