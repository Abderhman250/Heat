<?php

namespace Database\Factories;

use App\Models\BookingClassUser;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingClassUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = BookingClassUser::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'class_id' => ClassModel::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),  // Random quantity between 1 and 10
            'class_completed' => $this->faker->numberBetween(0, 1),  // Random completion status (0 or 1)
        ];
    }
}
