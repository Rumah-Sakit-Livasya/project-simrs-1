<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(MenusTableSeeder::class);
        // $this->call(BankSeeder::class);
        // $this->call(OrganizationSeeder::class);
        // $this->call(CompanySeeder::class);
        // $this->call(JobLevelSeeder::class);
        // $this->call(JobPositionSeeder::class);
        // $this->call(EmployeeSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AttendanceCodeSeeder::class);
        // $this->call(ShiftSeeder::class);
    }
}
