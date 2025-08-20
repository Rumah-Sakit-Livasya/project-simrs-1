<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farmasi_resep_harians', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_resep');
            $table->string('resep_manual')->nullable();
            $table->boolean('selesai')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_resep_harians');
    }
};
