<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\ClassType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory; // Import Faker Factory

class ClassModelFactory extends Factory
{
    protected $model = ClassModel::class;

    public function definition()
    {
        $this->faker->addProvider(new SportProvider($this->faker));

        return [
            'name' => $this->faker->sportClassName(),
            'description' => $this->faker->sportClassDescription(),
            'photo' => $this->faker->imageUrl(640, 480, 'sports', true),
            'room' => $this->faker->randomElement(['Gym Hall', 'Studio 1', 'Studio 2', 'Outdoor Field']),
            'seat_selection_required' => $this->faker->boolean,
            'capacity' => $this->faker->numberBetween(10, 50),
            'class_type_id' => ClassType::factory(),
        ];
    }
     
}
