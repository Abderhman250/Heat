<?php

use Database\Seeders\DesignQuestionsTableSeeder;
use Illuminate\Database\Seeder;

use Database\Seeders\InterestSeeder;
use Database\Seeders\LookupSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RegionTableSeed::class);
        // $this->call(CountryCitySeed::class);
        // $this->call(UsersTableSeed::class);
        // $this->call(ContentSeeder::class);
        // $this->call(InterestSeeder::class);
        $this->call(LookupSeeder::class);
        $this->call(DesignQuestionsTableSeeder::class);
        

    }
}
