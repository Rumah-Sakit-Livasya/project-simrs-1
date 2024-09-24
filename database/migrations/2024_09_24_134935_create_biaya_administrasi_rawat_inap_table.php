<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biaya_administrasi_rawat_inap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_penjamin_id')->constrained('group_penjamin')->cascadeOnDelete();
            $table->integer('persentase');
            $table->bigInteger('min_tarif')->default(0);
            $table->bigInteger('max_tarif')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('biaya_administrasi_rawat_inap')->insert([
            [
                'group_penjamin_id' => 1,
                'persentase' => 5,
                'min_tarif' => 0,
                'max_tarif' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_penjamin_id' => 2,
                'persentase' => 5,
                'min_tarif' => 0,
                'max_tarif' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_penjamin_id' => 3,
                'persentase' => 5,
                'min_tarif' => 0,
                'max_tarif' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_administrasi_rawat_inap');
    }
};
