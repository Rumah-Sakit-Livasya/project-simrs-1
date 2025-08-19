<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('procurement_purchase_request_non_pharmacy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date("tanggal_pr");
            $table->date("tanggal_app")->nullable();
            $table->string("kode_pr");
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("app_user_id")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("gudang_id")->constrained("warehouse_master_gudang")->cascadeOnDelete();
            $table->enum("tipe", ["normal", "urgent"])->default("normal");
            $table->integer("nominal");
            $table->enum("status" , ["draft", "final", "reviewed"])->default("draft");
            $table->string("keterangan")->nullable();
            $table->string("keterangan_approval")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_purchase_request_non_pharmacy');
    }
};
