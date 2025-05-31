<?php

use Database\Seeders\AppointmentsTableSeeder;
use Database\Seeders\BookingClassUserSeeder;
use Database\Seeders\BookingsSeeder;
use Database\Seeders\BookingsTableSeeder;
use Database\Seeders\ClassesTableSeeder;
use Database\Seeders\ClassTypesTableSeeder;
use Database\Seeders\DesignQuestionsTableSeeder;
use Illuminate\Database\Seeder;
 
use Database\Seeders\LevelSeeder;
use Database\Seeders\LookupSeeder;
use Database\Seeders\PlansTableSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SeatPointsTableSeeder;
use Database\Seeders\SectionPlanSeeder;
use Database\Seeders\UserPlansTableSeeder;
use Database\Seeders\UsersAndCoachesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
 
        $this->call(UsersTableSeed::class);
 
        $this->call(LookupSeeder::class);
 
        $this->call(LevelSeeder::class); 
        // $this->call(UsersAndCoachesSeeder::class);
        $this->call([
            ClassTypesTableSeeder::class,
            // ClassesTableSeeder::class,
            // SeatPointsTableSeeder::class,
            // SectionPlanSeeder::class,
            // PlansTableSeeder::class,
            // AppointmentsTableSeeder::class,
            // UserPlansTableSeeder::class,
            // BookingClassUserSeeder::class,
            // BookingsSeeder::class,
        ]);

    }
}
