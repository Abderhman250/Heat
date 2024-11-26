<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\SectionPlan;
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
            'type'=>$this->faker->randomElement([
                'First Timer Exclusive Offer',
                'Class Packages',
                'Memberships',
                'Ride Intro Offers - Now Open @Weho'
            ]),
            'class_id' => ClassModel::inRandomOrder()->first()->id,
            'total_classes' => $this->faker->numberBetween(5, 50),
            'description' => $this->faker->paragraph,
            'price'=> $this->faker->numberBetween(51, 250),
            'photo' => $this->faker->imageUrl(640, 480, 'sports', true),
            'section_plan_id' => SectionPlan::inRandomOrder()->first()->id,  // Link to a random section plan

        ];
    }
}
