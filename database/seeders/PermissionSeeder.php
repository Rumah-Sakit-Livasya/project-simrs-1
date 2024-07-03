<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'view companies', 'guard_name' => 'web'],
            ['name' => 'create companies', 'guard_name' => 'web'],
            ['name' => 'edit companies', 'guard_name' => 'web'],
            ['name' => 'delete companies', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $data) {
            Permission::create($data);
        }
        $admin = Role::find(1);
        $admin->givePermissionTo($permissions);
    }
}
