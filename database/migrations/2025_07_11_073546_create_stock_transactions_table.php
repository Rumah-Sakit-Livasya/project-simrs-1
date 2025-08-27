<?php

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('stock_id')->comment('Foreign Key to StoredBarangFarmasi / StoredBarangNonFarmasi Table');
            $table->string("stock_model")->comment('Model of the stock');
            $table->foreignId("source_id")->comment('ID of the source model that triggered the transaction');
            $table->string("source_model")->comment('Model that triggered the transaction');
            $table->string("source_controller")->comment('Controller that triggered the transaction');
            $table->enum("event_type", ["create", "update"])->comment('Type of event that triggered the transaction');
            $table->enum("transaction_type", ["in", "out"])->comment('Type of transaction (inbound or outbound)');
            // before and after qty
            $table->integer("before_qty")->nullable()->comment('Quantity before the transaction');
            $table->integer("after_qty")->comment('Quantity after the transaction');
            // before and after gudang
            $table->foreignId("before_gudang_id")->nullable()->constrained('warehouse_master_gudang')->comment('Gudang before the transaction');
            $table->foreignId("after_gudang_id")->constrained('warehouse_master_gudang')->comment('Gudang after the transaction');
            $table->foreignId('performed_by')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
