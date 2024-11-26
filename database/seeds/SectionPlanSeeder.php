<?php

namespace Database\Seeders;

use App\Models\SectionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SectionPlan::factory()->count(7)->create();  // Adjust the count as needed

    }
}
