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
        Schema::create('order_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('warehouse_master_gudang_id')->comment('Gudang')->constrained('warehouse_master_gudang'); // Asumsi ada tabel warehouses
            $table->string('no_order')->unique();
            $table->dateTime('order_date');
            $table->text('diagnosa_klinis')->nullable();
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
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
        Schema::dropIfExists('order_obats');
    }
};
