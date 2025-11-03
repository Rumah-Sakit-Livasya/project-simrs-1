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
        Schema::table('strs', function (Blueprint $table) {
            // Tambahkan kolom boolean 'is_lifetime' setelah 'str_expiry_date'
            // Defaultnya false (tidak seumur hidup)
            $table->boolean('is_lifetime')->default(false)->after('str_expiry_date');

            // Jadikan kolom tanggal kadaluarsa boleh null (nullable)
            $table->date('str_expiry_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strs', function (Blueprint $table) {
            $table->dropColumn('is_lifetime');
            $table->date('str_expiry_date')->nullable(false)->change(); // Kembalikan agar tidak nullable
        });
    }
};
