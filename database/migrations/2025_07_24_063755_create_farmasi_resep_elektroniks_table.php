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
        Schema::create('farmasi_resep_elektroniks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('cppt_id')->constrained('cppt')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('gudang_id')->nullable()->constrained('warehouse_master_gudang')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode_re')->unique();
            $table->string('resep_manual')->nullable();
            $table->integer('total');
            $table->boolean('processed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_resep_elektroniks');
    }
};
