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
        Schema::create('upload_files', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 100)->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('nama', 100)->nullable();
            $table->integer('tipe');
            $table->integer('hard_copy');
            $table->string('keterangan', 100)->nullable();
            $table->unsignedBigInteger('pic');
            $table->string('file');
            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');

            $table->foreign('pic')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_files');
    }
};
