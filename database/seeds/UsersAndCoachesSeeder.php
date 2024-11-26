<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coache;
use App\Models\Role;

class UsersAndCoachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();
        $adminRole = Role::where('id',1)->first();

        $userRole = Role::where('id',2)->first();
        // Create users who are coaches
        User::factory()
            ->count(10)
            ->state(['is_coache' => true])
            ->has(
                Coache::factory()
                    ->state(function (array $attributes, User $user) {
                        return ['user_id' => $user->id, 'username' => $user->username];
                    })
            )
            ->create()->each(function ($user) use ($userRole) {
                $user->syncRoles([$userRole->id]);
            });


            $adminUser = User::create([
                'username' => 'adminUser',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '123456789',
                'country_code' => '+962',
                'password' => bcrypt('adminpassword'), // Use bcrypt for password
                'gender' => 1,
                'email' => 'admin@mail.com',
                'dob' => '1980-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $adminUser->syncRoles([$adminRole->id]);

       
    }
}
