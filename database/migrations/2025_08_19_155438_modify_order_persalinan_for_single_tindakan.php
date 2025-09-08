<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, drop the old pivot table as it's no longer needed.
        Schema::dropIfExists('order_persalinan_detail');

        // Next, add the foreign key directly to the main order table.
        Schema::table('order_persalinan', function (Blueprint $table) {
            // This column will store the ID of the single procedure for this order.
            $table->foreignId('persalinan_id')
                ->nullable() // Or use ->required() if a procedure is always mandatory
                ->after('tipe_penggunaan_id') // Places the column in a logical spot
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate the pivot table if we roll back the migration.
        Schema::create('order_persalinan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_persalinan_id')->constrained('order_persalinan')->onDelete('cascade');
            $table->foreignId('persalinan_id')->constrained('persalinan')->onDelete('cascade');
            $table->timestamps();
        });

        // Drop the foreign key and the column from the main table.
        Schema::table('order_persalinan', function (Blueprint $table) {
            $table->dropForeign(['persalinan_id']);
            $table->dropColumn('persalinan_id');
        });
    }
};
