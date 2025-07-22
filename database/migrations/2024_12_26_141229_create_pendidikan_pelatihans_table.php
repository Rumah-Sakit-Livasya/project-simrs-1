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
        Schema::create('pendidikan_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pembicara');
            $table->enum('type', ['internal', 'eksternal']);
            $table->string('tempat');
            $table->string('datetime');
            $table->text('catatan');
            $table->boolean('is_verif')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikan_pelatihan');
    }
};
