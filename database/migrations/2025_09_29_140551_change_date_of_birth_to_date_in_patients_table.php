<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // File: xxxx_xx_xx_xxxxxx_change_date_of_birth_to_date_in_patients_table.php

    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Ganti menjadi dateTime
            $table->dateTime('date_of_birth')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Pengembaliannya tetap sama
            $table->string('date_of_birth', 255)->nullable(false)->change();
        });
    }
};
