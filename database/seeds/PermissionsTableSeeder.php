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
                  'id' => 1,
                  'name' => 'create',
        ]);

        DB::table('permissions')->insert([
                  'id' => 2,
                  'name' => 'read',
        ]);

        DB::table('permissions')->insert([
                  'id' => 3,
                  'name' => 'update',
        ]);

        DB::table('permissions')->insert([
                  'id' => 4,
                  'name' => 'delete',
        ]);

        
    }
}
