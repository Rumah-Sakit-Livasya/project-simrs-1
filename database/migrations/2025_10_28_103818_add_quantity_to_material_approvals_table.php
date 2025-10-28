<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_approvals', function (Blueprint $table) {
            // Tambahkan kolom quantity setelah 'type_or_model'
            $table->decimal('quantity', 15, 2)->nullable()->after('type_or_model');

            // Tambahkan kolom satuan setelah 'quantity'
            // Kita akan relasikan ke tabel satuan yang sudah ada
            $table->foreignId('satuan_id')->nullable()->after('quantity')->constrained('warehouse_satuan_barang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('material_approvals', function (Blueprint $table) {
            $table->dropForeign(['satuan_id']);
            $table->dropColumn('satuan_id');
            $table->dropColumn('quantity');
        });
    }
};
