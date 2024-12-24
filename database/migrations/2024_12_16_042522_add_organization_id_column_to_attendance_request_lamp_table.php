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
        Schema::table('attendance_request_lamp', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->after('tanggal');

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_request_lamp', function (Blueprint $table) {
            $table->dropForeign(['organization_id']); // Menghapus foreign key
            $table->dropColumn('organization_id');   // Menghapus kolom
        });
    }
};
