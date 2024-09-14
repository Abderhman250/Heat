<?php

namespace Database\Seeders; // Ensure the namespace is correct

use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Level::insert([
            [
                'title_levels' => 'Beginner',
                'required_classes' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_levels' => 'Intermediate',
                'required_classes' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_levels' => 'Advanced',
                'required_classes' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_levels' => 'Expert',
                'required_classes' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
