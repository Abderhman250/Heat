<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class; // Adjust the namespace if necessary

    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'country_code' => $this->faker->countryCode,
            'gender' => $this->faker->randomElement([0, 1]), // Assuming 0 = female, 1 = male
            'email' => $this->faker->unique()->safeEmail,
            'dob' => $this->faker->date('Y-m-d', '2005-12-31'),
            'photo' => $this->faker->imageUrl(640, 480, 'people'),
            'password' => Hash::make('password'), // Default password
            'facebook_id' => null,
            'google_id' => null,
            'email_verified_at' => now(),
            'is_coache' => $this->faker->boolean(30), // 30% chance of being a coach
            'is_active' => true,
            'otp' => null,
            'number_class' => $this->faker->numberBetween(0, 100),
            'level_id' => null, // Set this if you have levels
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
