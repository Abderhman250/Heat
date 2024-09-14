<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $plans = Plan::all();

        foreach ($users as $user) {
            // Each user subscribes to a few plans
            $userPlans = $plans->random(2);

            foreach ($userPlans as $plan) {
                $startDate = now()->subDays(rand(0, 30));
                $endDate = (clone $startDate)->addDays(rand(30, 90));

                $user->plans()->attach($plan->id, [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
