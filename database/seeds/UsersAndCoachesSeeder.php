<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coache;

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
            ->create();
    }
}
