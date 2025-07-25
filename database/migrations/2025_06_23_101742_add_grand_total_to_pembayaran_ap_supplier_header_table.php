 <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        // dalam file migrasi...
        public function up()
        {
            Schema::table('pembayaran_ap_supplier_headers', function (Blueprint $table) {
                $table->decimal('grand_total_pembayaran', 15, 2)->default(0)->after('total_pembayaran');
            });
        }

        public function down()
        {
            Schema::table('pembayaran_ap_supplier_headers', function (Blueprint $table) {
                $table->dropColumn('grand_total_pembayaran');
            });
        }
    };
