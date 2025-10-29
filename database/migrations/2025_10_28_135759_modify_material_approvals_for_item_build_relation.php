<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_approvals', function (Blueprint $table) {
            // Tambahkan foreign key ke master item
            $table->foreignId('project_build_item_id')->nullable()->after('document_id')->constrained('project_build_items')->onDelete('cascade');

            // Jadikan kolom `material_name` nullable karena sekarang nama diambil dari relasi
            // Kita tidak menghapusnya agar data lama tidak hilang
            $table->string('material_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('material_approvals', function (Blueprint $table) {
            $table->dropForeign(['project_build_item_id']);
            $table->dropColumn('project_build_item_id');
            $table->string('material_name')->nullable(false)->change();
        });
    }
};
