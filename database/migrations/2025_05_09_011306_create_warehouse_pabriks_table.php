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
        Schema::create('warehouse_pabrik', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('telp')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('aktif')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_pabrik');
    }
};
