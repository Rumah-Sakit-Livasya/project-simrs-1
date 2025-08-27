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
}
