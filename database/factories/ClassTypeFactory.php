<?php

namespace Database\Factories;

use App\Models\ClassType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassTypeFactory extends Factory
{
    protected $model = ClassType::class;

    public function definition()
    {
        return [
            'type_name' => $this->faker->word,
            'booking_process' => $this->faker->randomElement(['Online', 'In-Person']),
        ];
    }
}
