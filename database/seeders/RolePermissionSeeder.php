<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'menage users']);
        Permission::create(['name'=> 'menage data']);
        Permission::create(['name'=> 'menage data sekolah']);
        Permission::create(['name'=> 'menage pengajuan']);
        Permission::create(['name'=> 'menage validation']);


        $admin = Role::create(['name' => 'admin']);
        $operator_sekolah = Role::create(['name' => 'operator_sekolah']);

        $admin->givePermissionTo(Permission::all());
        $operator_sekolah->givePermissionTo([
            'menage data sekolah',
            'menage pengajuan',
            'menage data',
        ]);
    }
}
