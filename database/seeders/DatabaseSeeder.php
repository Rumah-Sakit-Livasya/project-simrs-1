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
        $this->call(GroupCOASeeders::class);
        $this->call(ChartOfAccountSeeders::class);
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
    }
}
