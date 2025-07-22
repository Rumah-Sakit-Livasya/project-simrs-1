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
        Schema::create('kepustakaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->enum('kategori', ['Regulasi', 'Laporan', 'Perizinan', 'Mutu dan Manajemen Resiko', 'File Unit Lainnya'])->nullable();
            $table->string('name');
            $table->enum('type', ['file', 'folder']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->bigInteger('size')->default(0);
            $table->string('file')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Optional: foreign key constraint untuk parent_id
            $table->foreign('parent_id')->references('id')->on('kepustakaan')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepustakaan');
    }
};
