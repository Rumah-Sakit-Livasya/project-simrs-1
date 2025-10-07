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
        Schema::create('project_internals', function (Blueprint $table) {
            $table->id();

            // Kolom relasi ke tabel 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->dateTime('datetime');
            $table->string('name');
            $table->text('description')->nullable();

            // Kolom status dengan nilai default 'pending'
            $table->enum('status', ['pending', 'on-progress', 'done'])->default('pending');

            // Kolom untuk mencatat kapan proyek selesai, bisa null
            $table->dateTime('done_at')->nullable();

            $table->timestamps(); // Ini akan membuat kolom created_at dan updated_at
            $table->softDeletes(); // Ini akan membuat kolom deleted_at untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_internals');
    }
};
