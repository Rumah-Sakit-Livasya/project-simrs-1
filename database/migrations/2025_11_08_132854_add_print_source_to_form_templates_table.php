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
        Schema::table('form_templates', function (Blueprint $table) {
            // Tipe TEXT atau LONGTEXT agar bisa menampung HTML yang panjang
            $table->longText('print_source')->nullable()->after('form_source');
        });
    }

    public function down(): void
    {
        Schema::table('form_templates', function (Blueprint $table) {
            $table->dropColumn('print_source');
        });
    }
};
