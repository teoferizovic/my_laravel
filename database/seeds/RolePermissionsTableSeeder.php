<?php

use Illuminate\Database\Seeder;

class RolePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_permissions')->insert([
                  'role_id' => 1,
                  'permission_id' => 1,
        ]);

        DB::table('role_permissions')->insert([
                  'role_id' => 1,
                  'permission_id' => 2,
        ]);

        DB::table('role_permissions')->insert([
                  'role_id' => 1,
                  'permission_id' => 3,
        ]);

        DB::table('role_permissions')->insert([
                  'role_id' => 1,
                  'permission_id' => 4,
        ]);

         DB::table('role_permissions')->insert([
                  'role_id' => 2,
                  'permission_id' => 2,
        ]);
    }
}
