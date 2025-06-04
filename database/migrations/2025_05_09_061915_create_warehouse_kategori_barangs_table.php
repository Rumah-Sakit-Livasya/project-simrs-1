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
        Schema::create('warehouse_kategori_barang', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nama');
            $table->foreignId('coa_inventory')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->foreignId('coa_sales_outpatient')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->foreignId('coa_cogs_outpatient')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->foreignId('coa_sales_inpatient')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->foreignId('coa_cogs_inpatient')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->foreignId('coa_adjustment_daily')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->foreignId('coa_adjustment_so')->nullable()->constrained("chart_of_account")->cascadeOnDelete();
            $table->boolean('konsinsyasi')->default(false);
            $table->boolean('aktif')->default(true);
            $table->string('kode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_kategori_barang');
    }
};
