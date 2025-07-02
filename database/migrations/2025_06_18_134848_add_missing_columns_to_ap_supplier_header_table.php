<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ap_supplier_header', function (Blueprint $table) {
            // Menambahkan kolom yang hilang
            $table->decimal('retur', 15, 2)->default(0)->after('ppn_nominal');
            $table->decimal('materai', 15, 2)->default(0)->after('retur');
            $table->string('no_faktur_pajak')->nullable()->after('due_date');
            $table->string('no_faktur_pajak_retur')->nullable()->after('no_faktur_pajak');
        });
    }

    public function down(): void
    {
        Schema::table('ap_supplier_header', function (Blueprint $table) {
            $table->dropColumn(['retur', 'materai', 'no_faktur_pajak', 'no_faktur_pajak_retur']);
        });
    }
};
