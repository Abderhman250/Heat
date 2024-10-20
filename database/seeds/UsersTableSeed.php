<?php

use App\Models\Role;
use App\Models\User;
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
        DB::transaction(function () {
            // Create roles
            $adminRole = Role::create([
                'name' => 'admin',
                'display_name' => 'User Administrator',
                'description' => 'User is allowed to manage and edit other users',
            ]);

            $userRole = Role::create([
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'User is allowed',
            ]);

            // Create a user
            $user = User::create([
                'username' => 'shallan',
                'first_name' => 'The',
                'last_name' => 'doctor',
                'phone' => '798006704',
                'country_code' => '+962',
                'password' => bcrypt('aabb1986'), // Using bcrypt for password
                'gender' => 1,
                'email' => 'tareq@mail.com',
                'dob' => '1996-12-17',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->syncRoles([$userRole->id]);

            // Assign the admin role to the user
              // Use the admin role here
        });
    
    }
}
