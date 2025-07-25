<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama tabel ini spesifik untuk detail AP Non-GR
        Schema::create('ap_non_grn_details', function (Blueprint $table) {
            $table->id();
            // Foreign key menunjuk ke tabel master AP Supplier
            $table->foreignId('ap_supplier_header_id')->constrained('ap_supplier_header')->cascadeOnDelete();
            $table->foreignId('coa_id')->constrained('chart_of_account');
            $table->foreignId('cost_center_id')->nullable()->constrained('chart_of_account');
            $table->string('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ap_non_gr_details');
    }
};
