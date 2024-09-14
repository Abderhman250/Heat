<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CoacheFactory extends Factory
{
    protected $model = \App\Models\Coache::class; // Adjust the namespace if necessary

    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'specialty' => $this->faker->jobTitle,
            'bio' => $this->faker->paragraph,
            'profile_photo' => $this->faker->imageUrl(640, 480, 'people'),
            'user_id' => null, // We'll set this in the seeder to ensure proper linkage
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
