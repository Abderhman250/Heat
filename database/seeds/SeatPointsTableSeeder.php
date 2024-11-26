<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\SeatPoint;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatPointsTableSeeder extends Seeder
{
    public function run()
    {
        // Get all classes
        $classes = ClassModel::all();
        $countLine = 0;
        $lines =  ["F-3", "F-4","F-5","F-6","T-6","T-5","T-4","T-3","T-2","T-1","F-1","F-2"];

        foreach ($classes as $class) {
            // Loop to create lines with two SeatPoints per line
            for ($i = 0; $i < $class->capacity / 2; $i++) {
         
                if (empty($lines))
                    break;

                $line =  array_shift($lines);
         
                SeatPoint::factory()
                    ->count(2)  // Two seats per line
                    ->state([
                        'class_id' => $class->id,
                        'line' => $line, // Set the same line for both seats
                    ])
                    ->create();

                $countLine++;
            }
        }
        // foreach ($classes as $class) {
        //     // Loop to create lines with two SeatPoints per line
        //     for ($i = 0; $i < $class->capacity / 2; $i++) {
        //         // Alternate between 'T.' and 'L.' lines
        //         if ($countLine % 2 == 0)
        //             $line = 'T.' . \Faker\Factory::create()->numberBetween(1, 1000);
        //         else
        //             $line = 'F.' . \Faker\Factory::create()->numberBetween(1, 1000);
        //         // Create two SeatPoints per line
        //         SeatPoint::factory()
        //             ->count(2)  // Two seats per line
        //             ->state([
        //                 'class_id' => $class->id,
        //                 'line' => $line, // Set the same line for both seats
        //             ])
        //             ->create();

        //         $countLine++;
        //     }
        // }
    }
}
