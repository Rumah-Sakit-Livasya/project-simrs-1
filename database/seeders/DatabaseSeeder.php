<?php

namespace Database\Seeders;

use App\Models\Keuangan\Type;
use App\Models\Target;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
                // Aktifkan sesuai kebutuhan:
                // $this->call(GroupCOASeeders::class);
                // $this->call(ChartOfAccountSeeders::class);


        // Type::insert([
        //     ['id' => 1, 'nama' => 'Pemasukan', 'created_at' => now(), 'updated_at' => now()],
        //     ['id' => 2, 'nama' => 'Pengeluaran', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // $this->call(AccountReceivableSeeder::class);
        // $this->call([
        //         RncCenterSeeder::class,
        // ]);
        // $this->call([
        //     WasteCategorySeeder::class,
        //     VehicleSeeder::class,
        // ]);
        // $this->call([
        //     LinenCategorySeeder::class,
        //     LinenTypeSeeder::class,
        // ]);

<<<<<<< HEAD
                // $this->call(AccountReceivableSeeder::class);
                // $this->call([
                //         RncCenterSeeder::class,
                // ]);

                $this->call([
                        // 1. Seeder untuk tabel pendukung
                        KategoriPersalinanSeeder::class,
                        TipePersalinanSeeder::class,
                        // KelasRawatSeeder::class,
                        // GroupPenjaminSeeder::class,

                        // 2. Seeder untuk data persalinan
                        PersalinanCompleteSeeder::class,

                        // 3. Seeder untuk tarif (opsional - bisa dijalankan terpisah karena data besar)
                        // CompleteTarifPersalinanSeeder::class,
                ]);
        }
=======
        $this->call([
            JenisKegiatanSeeder::class,
            // InspectionItemSeeder::class,
            // InternalVehicleSeeder::class,
            // WorkshopVendorSeeder::class,
            // DriverSeeder::class,
            // VehicleLogSeeder::class,
            // Daftarkan seeder lain di sini jika ada
        ]);


        // Membuat 200 data input limbah harian
        // \App\Models\DailyWasteInput::factory(200)->create();

        // Membuat 100 data pengangkutan limbah
        // \App\Models\WasteTransport::factory(100)->create();


    }
>>>>>>> 4b4212270b6af1b7fa68d8431d1e236363f8bff4
}
