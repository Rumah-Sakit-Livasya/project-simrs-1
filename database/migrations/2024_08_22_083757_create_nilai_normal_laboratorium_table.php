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
        if (!Schema::hasTable('nilai_normal_laboratorium')) {
            Schema::create('nilai_normal_laboratorium', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal');
                $table->foreignId('user_input')->constrained('users')->cascadeOnDelete();
                $table->foreignId('parameter_laboratorium_id')->constrained('parameter_laboratorium')->cascadeOnDelete();
                $table->string('jenis_kelamin', 100);
                $table->string('dari_umur', 100);
                $table->string('sampai_umur', 100);
                $table->float('min', 8, 2);
                $table->float('max', 8, 2);
                $table->string('nilai_normal')->nullable();
                $table->text('keterangan')->nullable();
                $table->text('hasil')->nullable();
                $table->float('min_kritis')->nullable();
                $table->float('max_kritis')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        } else {
            Schema::dropIfExists('nilai_normal_laboratorium');
            Schema::create('nilai_normal_laboratorium', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal');
                $table->foreignId('user_input')->constrained('users')->cascadeOnDelete();
                $table->foreignId('parameter_laboratorium_id')->constrained('parameter_laboratorium')->cascadeOnDelete();
                $table->string('jenis_kelamin', 100);
                $table->string('dari_umur', 100);
                $table->string('sampai_umur', 100);
                $table->float('min', 8, 2);
                $table->float('max', 8, 2);
                $table->string('nilai_normal')->nullable();
                $table->text('keterangan')->nullable();
                $table->text('hasil')->nullable();
                $table->float('min_kritis')->nullable();
                $table->float('max_kritis')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_normal_laboratorium');
    }
};
