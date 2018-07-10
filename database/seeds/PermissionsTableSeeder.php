<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
                  'name' => 'create',
                  'role_id' => 1,
        ]);

        DB::table('permissions')->insert([
                  'name' => 'read',
                  'role_id' => 1,
        ]);

        DB::table('permissions')->insert([
                  'name' => 'update',
                  'role_id' => 1,
        ]);

        DB::table('permissions')->insert([
                  'name' => 'delete',
                  'role_id' => 1,
        ]);

        DB::table('permissions')->insert([
                  'name' => 'read',
                  'role_id' => 2,
        ]);
        
    }
}
