    <?php

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
            Schema::table('jasa_dokter', function (Blueprint $table) {
                // Pastikan 'order_tindakan_medis_id' sudah ada di tabel jasa_dokter
                // Jika kolom order_tindakan_medis_id tidak ada, migrasi ini akan gagal
                $table->foreignId('tagihan_pasien_id')
                    ->nullable()
                    ->constrained('tagihan_pasien')
                    ->after('order_tindakan_medis_id'); // Menambahkan kolom setelah 'order_tindakan_medis_id'
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('jasa_dokter', function (Blueprint $table) {
                // Pastikan untuk menghapus foreign key terlebih dahulu sebelum menghapus kolom
                $table->dropForeign(['tagihan_pasien_id']);
                $table->dropColumn('tagihan_pasien_id');
            });
        }
    };
