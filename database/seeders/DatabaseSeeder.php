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
        // $this->call(EthnicSeeder::class);
        // Target::factory()->count(100)->create();
        // $this->call(RolePermissionSeeder::class);
        // $this->call(MenusTableSeeder::class);
        // $this->call(BankSeeder::class);
        // $this->call(OrganizationSeeder::class);
        // $this->call(CompanySeeder::class);
        // $this->call(JobLevelSeeder::class);
        // $this->call(JobPositionSeeder::class);
        // $this->call(EmployeeSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AttendanceCodeSeeder::class);
        // $this->call(ShiftSeeder::class);
        // $this->call(DepartementSeeder::class);
        // $this->call(EthnicSeeder::class);
        // $this->call(GroupPenjaminSeeder::class);
        // $this->call(PenjaminSeeder::class);
        // $this->call([
        //     TimeScheduleSeeder::class,
        //     TimeScheduleEmployeeSeeder::class,
        //     // Seeder lainnya jika ada
        // ]);

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

        $this->call([
            // NursingDiagnosisSeeder::class,
            // InterventionSeeder::class, // <-- TAMBAHKAN INI
            // DocumentCategorySeeder::class, // <-- TAMBAHKAN INI
            // Anda bisa menambahkan seeder lain di sini
        ]);


        // $this->call([
        // JenisKegiatanSeeder::class,
        // InspectionItemSeeder::class,
        // InternalVehicleSeeder::class,
        // WorkshopVendorSeeder::class,
        // DriverSeeder::class,
        // VehicleLogSeeder::class,
        // Daftarkan seeder lain di sini jika ada
        // ]);


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
            // KategoriPersalinanSeeder::class,
            // TipePersalinanSeeder::class,
            // KelasRawatSeeder::class,
            // GroupPenjaminSeeder::class,

            // 2. Seeder untuk data persalinan
            // PersalinanCompleteSeeder::class,

            // 3. Seeder untuk tarif (opsional - bisa dijalankan terpisah karena data besar)
            // CompleteTarifPersalinanSeeder::class,
            // Icd10Seeder::class,
            Icd9Seeder::class,

        ]);
    }
}
