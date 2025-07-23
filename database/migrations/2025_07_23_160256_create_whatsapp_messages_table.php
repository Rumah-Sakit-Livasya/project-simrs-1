<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable()->unique(); // ID unik dari WhatsApp
            $table->string('phone_number'); // Nomor pengirim/penerima
            $table->string('contact_name')->nullable();
            $table->text('message')->nullable();
            $table->string('file_path')->nullable(); // Untuk menyimpan path file/lampiran
            $table->enum('direction', ['in', 'out']); // 'in' untuk masuk, 'out' untuk keluar
            $table->enum('status', ['sending', 'sent', 'delivered', 'read', 'failed'])->default('sending');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
