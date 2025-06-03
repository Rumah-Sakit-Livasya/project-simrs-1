<?php

use App\Models\User;
use App\Models\WarehouseSupplier;
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
        Schema::create('procurement_purchase_order_pharmacy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string("kode_po");
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("app_user_id")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("ceo_app_user_id")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("supplier_id")->constrained("warehouse_supplier")->cascadeOnDelete();
            $table->date("tanggal_po");
            $table->date("tanggal_app")->nullable();
            $table->date("tanggal_app_ceo")->nullable();
            $table->date("tanggal_kirim")->nullable();
            $table->boolean("is_auto")->default(false);
            $table->enum('top', ["COD", "7HARI", "14HARI", "21HARI", "24HARI", "30HARI", "37HARI", "40HARI", "45HARI"])->nullable();
            $table->enum('tipe_top', ["SETELAH_TUKAR_FAKTUR", "SETELAH_TERIMA_BARANG"])->default("SETELAH_TUKAR_FAKTUR");
            $table->enum("tipe", ["normal", "urgent"])->default("normal");
            $table->enum("status", ["draft", "final", "revision"])->default("draft");
            $table->enum("approval", ["unreviewed", "approve", "reject", "revision"])->default("unreviewed");
            $table->enum("approval_ceo", ["unreviewed", "approve", "reject", "revision"])->default("unreviewed");
            $table->integer("ppn");
            $table->integer("nominal");
            $table->string("pic_terima")->nullable();
            $table->string("keterangan")->nullable();
            $table->string("keterangan_approval")->nullable();
            $table->string("keterangan_approval_ceo")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_purchase_order_pharmacy');
    }
};
