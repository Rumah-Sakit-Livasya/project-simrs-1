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
        Schema::create('farmasi_reseps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date('order_date');
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('otc_id')->nullable()->constrained('registration_otc')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('re_id')->nullable()->constrained('farmasi_resep_elektroniks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode_resep')->unique();
            $table->string('alamat')->nullable();
            $table->string('resep_manual')->nullable();
            $table->enum('embalase', ['tidak', 'item', 'racikan'])->default('tidak');
            $table->string('no_telp')->nullable();
            $table->integer('total')->nullable();
            $table->boolean('bmhp')->default(false);
            $table->boolean('kronis')->default(false);
            $table->boolean('billed')->default(false);
            $table->boolean('handed')->default(false);
            $table->boolean('dispensing')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_reseps');
    }
};
