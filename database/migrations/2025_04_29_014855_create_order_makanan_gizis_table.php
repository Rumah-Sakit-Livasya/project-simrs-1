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
        Schema::create('order_makanan_gizi', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('order_id')->constrained('order_gizi')->onDelete('cascade');
            $table->foreignId('makanan_id')->constrained('makanan_gizi')->onDelete('cascade');
            $table->integer('harga');
            $table->integer('persentase_habis')->default(100)->unsigned()->check('persentase_habis >= 0 AND persentase_habis <= 100');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_makanan_gizi');
    }
};
