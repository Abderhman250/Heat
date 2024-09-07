<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'phone' => '798006704',
            'first_name' => 'The',
            'last_name' => 'doctor',
            'country_code' => '+962',
            'password' => bcrypt('aabb1986'),
            'gender' => 1,
            'country_id' => 2,
            'city_id' => 62037,
            'email' => 'tareq@mail.com',
            'dob' => '1996-12-17',
            'is_admin' => 1,
            'role' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
