<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_counters', function (Blueprint $table) {
            $table->id();
            $table->string('bulan_tahun', 10)->unique()->comment('Format: YYYY-MM');
            $table->integer('counter')->default(0)->comment('Counter untuk nomor invoice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_counters');
    }
}
