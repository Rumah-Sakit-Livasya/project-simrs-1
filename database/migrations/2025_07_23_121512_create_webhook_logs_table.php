<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();

            // Kolom untuk data penting yang diekstrak
            $table->string('session_id')->nullable()->comment('ID Sesi Pengirim');
            $table->string('message_id')->unique()->comment('ID unik dari pesan');
            $table->string('sender_jid')->comment('JID (Jabber ID) pengirim');
            $table->string('group_jid')->nullable()->comment('JID grup jika pesan dari grup');
            $table->string('sender_name')->nullable()->comment('Nama kontak pengirim (pushName)');
            $table->text('message_text')->nullable()->comment('Isi teks dari pesan');
            $table->timestamp('message_timestamp')->nullable()->comment('Waktu pesan dikirim');

            // Kolom untuk menyimpan seluruh payload JSON mentah
            // Tipe 'json' sangat direkomendasikan jika database Anda mendukungnya (MySQL 5.7+, PostgreSQL)
            // Jika tidak, gunakan tipe 'text'.
            $table->json('full_payload');

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
