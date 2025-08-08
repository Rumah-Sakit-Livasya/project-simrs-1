<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_stock_opname_gudang', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->foreignId("start_user_id")->constrained("users")->comment("Current logged in user")->cascadeOnDelete();
            $table->foreignId("finish_user_id")->nullable()->constrained("users")->comment("Current logged in user")->cascadeOnDelete();
            $table->dateTime("start");
            $table->dateTime("finish")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_opname_gudang');
    }
};
