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
        Schema::create('form_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_kategori_id');

            $table->foreign('form_kategori_id')->references('id')->on('form_kategori');
            $table->string('nama_form');
            $table->longText('form_source');
            $table->boolean('is_active')->default(true);
            
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            
            $table->unsignedBigInteger('modify_by');
            $table->foreign('modify_by')->references('id')->on('users')->cascadeOnDelete();
            
            $table->longText('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_templates');
    }
};
