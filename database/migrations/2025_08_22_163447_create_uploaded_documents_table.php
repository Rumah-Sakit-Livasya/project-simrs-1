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
        Schema::create('uploaded_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('document_category_id')->constrained('document_categories');
            $table->text('description')->nullable();
            $table->string('original_filename');
            $table->string('stored_filename')->unique();
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedInteger('file_size'); // in bytes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_documents');
    }
};
