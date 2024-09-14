<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LookupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->word,
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
