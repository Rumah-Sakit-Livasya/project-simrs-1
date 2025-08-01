<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prosedur_operasi', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('tindakan_id', 'tindakan_operasi_id');

            // Add jenis_operasi_id
            $table->foreignId('jenis_operasi_id')
                ->after('order_operasi_id')
                ->nullable()
                ->constrained('jenis_operasi')
                ->onDelete('restrict');

            // Add ass dokter operator (1-3)
            $table->foreignId('ass_dokter_operator_1_id')
                ->after('dokter_operator_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Asisten dokter operator 1');

            $table->foreignId('ass_dokter_operator_2_id')
                ->after('ass_dokter_operator_1_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Asisten dokter operator 2');

            $table->foreignId('ass_dokter_operator_3_id')
                ->after('ass_dokter_operator_2_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Asisten dokter operator 3');

            // Make old single operator nullable
            $table->foreignId('dokter_operator_id')
                ->nullable()
                ->change();

            // Add additional doctors (1-5)
            $table->foreignId('dokter_tambahan_1_id')
                ->after('dokter_tambahan_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Dokter tambahan 1');

            $table->foreignId('dokter_tambahan_2_id')
                ->after('dokter_tambahan_1_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Dokter tambahan 2');

            $table->foreignId('dokter_tambahan_3_id')
                ->after('dokter_tambahan_2_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Dokter tambahan 3');

            $table->foreignId('dokter_tambahan_4_id')
                ->after('dokter_tambahan_3_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Dokter tambahan 4');

            $table->foreignId('dokter_tambahan_5_id')
                ->after('dokter_tambahan_4_id')
                ->nullable()
                ->constrained('doctors')
                ->comment('Dokter tambahan 5');

            // Add status if not exists
            if (!Schema::hasColumn('prosedur_operasi', 'status')) {
                $table->string('status')
                    ->after('komplikasi')
                    ->default('draft')
                    ->comment('draft/final');
            }
        });
    }

    public function down()
    {
        Schema::table('prosedur_operasi', function (Blueprint $table) {
            // Rename back column
            $table->renameColumn('tindakan_operasi_id', 'tindakan_id');

            $table->dropForeign(['jenis_operasi_id']);
            $table->dropColumn('jenis_operasi_id');

            // Drop ass dokter operator (1-3)
            $table->dropForeign(['ass_dokter_operator_1_id']);
            $table->dropColumn('ass_dokter_operator_1_id');

            $table->dropForeign(['ass_dokter_operator_2_id']);
            $table->dropColumn('ass_dokter_operator_2_id');

            $table->dropForeign(['ass_dokter_operator_3_id']);
            $table->dropColumn('ass_dokter_operator_3_id');

            // Drop dokter tambahan (1-5)
            $table->dropForeign(['dokter_tambahan_1_id']);
            $table->dropColumn('dokter_tambahan_1_id');

            $table->dropForeign(['dokter_tambahan_2_id']);
            $table->dropColumn('dokter_tambahan_2_id');

            $table->dropForeign(['dokter_tambahan_3_id']);
            $table->dropColumn('dokter_tambahan_3_id');

            $table->dropForeign(['dokter_tambahan_4_id']);
            $table->dropColumn('dokter_tambahan_4_id');

            $table->dropForeign(['dokter_tambahan_5_id']);
            $table->dropColumn('dokter_tambahan_5_id');

            // Revert dokter_operator_id to NOT NULL
            $table->foreignId('dokter_operator_id')
                ->nullable(false)
                ->change();

            // Drop status if exists
            if (Schema::hasColumn('prosedur_operasi', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
