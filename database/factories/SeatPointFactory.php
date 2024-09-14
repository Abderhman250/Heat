<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\SeatPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatPointFactory extends Factory
{
    protected $model = SeatPoint::class;

    public function definition()
    {
        $this->faker->addProvider(new SportProvider($this->faker));

         return [
            'seat_number' => 'E.' . $this->faker->numberBetween(1, 1000),
            'line'=> null,
            'class_id' => null,
            'note' =>$this->faker->sportClassDescription(),
        ];
    }
}
