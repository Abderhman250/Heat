<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plan_name' => $this->faker->randomElement([
                'Beginner Plan',
                'Intermediate Plan',
                'Advanced Plan',
                'Yoga Mastery Plan',
                'Pilates Pro Plan',
                'Weight Loss Plan',
                'Strength Training Plan',
                'Endurance Plan',
                'Flexibility Plan'
            ]),
            'class_id' => ClassModel::inRandomOrder()->first()->id,
            'total_classes' => $this->faker->numberBetween(5, 50),
            'description' => $this->faker->paragraph,
            'photo' => $this->faker->imageUrl(640, 480, 'sports', true),
        ];
    }
}
