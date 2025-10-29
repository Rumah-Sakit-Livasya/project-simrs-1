<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('current_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_build_item_id')->constrained('project_build_items')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang')->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();

            // Kunci unik gabungan agar tidak ada duplikasi item di gudang yang sama
            $table->unique(['project_build_item_id', 'gudang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('current_stocks');
    }
};
