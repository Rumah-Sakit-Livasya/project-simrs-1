<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petty_cash_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petty_cash_id'); // relasi ke header
            $table->unsignedBigInteger('coa_id'); // relasi ke chart of account
            $table->unsignedBigInteger('cost_center_id')->nullable(); // tambahkan ini
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 18, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('petty_cash_id')->references('id')->on('petty_cash')->onDelete('cascade');
            $table->foreign('coa_id')->references('id')->on('chart_of_account')->onDelete('cascade');
            $table->foreign('cost_center_id')->references('id')->on('rnc_centers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_detail');
    }
};
