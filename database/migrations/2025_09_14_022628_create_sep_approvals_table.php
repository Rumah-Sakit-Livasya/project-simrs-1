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
        Schema::create('sep_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('nokartu');
            $table->enum('jns_pelayanan', ['Rawat Inap', 'Rawat Jalan']);
            $table->string('jnspengajuan');
            $table->date('tglsep');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak'])->default('Diajukan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sep_approvals');
    }
};
