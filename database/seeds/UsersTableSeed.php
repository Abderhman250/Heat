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
            "username"=>"shallan",
            'first_name' => 'The',
            'last_name' => 'doctor',
            'phone' => '798006704',
            'country_code' => '+962',
            'password' => bcrypt('aabb1986'),
            'gender' => 1,
            'email' => 'tareq@mail.com',
            'dob' => '1996-12-17',
            'created_at' => now(),
            'updated_at' => now()
        ]);

   
    }
}
