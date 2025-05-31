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
        $seat = ["F-3", "F-4","F-5","F-6","T-6","T-5","T-4","T-3","T-2","T-1","F-1","F-2"];

        return [
            'name'=>$this->faker->randomElement([
                'Spinning room (bikes room)',
                'Group strength / endurance class',
                'Yoga studio',
            ]),
            'description' => $this->faker->sportClassDescription(),
            'photo' => $this->faker->imageUrl(640, 480, 'sports', true),
            'room' => $this->faker->randomElement(['Gym Hall', 'Studio 1', 'Studio 2', 'Outdoor Field']),
            'seat_selection_required' => $this->faker->boolean,
            // 'capacity' => $this->faker->numberBetween(10, 50),
            'capacity' =>count($seat),
            'class_type_id' => ClassType::factory(),
        ];
    }
     
}
