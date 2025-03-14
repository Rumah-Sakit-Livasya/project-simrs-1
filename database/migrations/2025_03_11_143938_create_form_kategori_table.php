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
        Schema::create('form_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 30);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('entry_by');
            $table->unsignedBigInteger('modify_id')->nullable();
            
            $table->foreign('entry_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('modify_id')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_kategori');
    }
};
