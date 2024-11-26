<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SectionPlan>
 */
class SectionPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'section_name' => $this->faker->randomElement([
                'Introductory Section',
                'Advanced Techniques',
                'Core Principles',
                'Stretching & Flexibility',
                'Strength Training',
                'Endurance Building',
                'Nutrition Plan'
            ]),
            'description' => $this->faker->paragraph,
        ];
    }
}
