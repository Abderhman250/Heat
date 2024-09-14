<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\ClassModel;
use App\Models\Coache;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

 
            $startTime = $this->faker->dateTimeBetween('now', '+1 month');
            $endTime = (clone $startTime)->modify('+1 hour');  // Ensure the end time is one hour after start time
    
            return [
                'appointment_name' => $this->faker->sentence(3),  // Generates a random appointment name
                'class_id' => ClassModel::inRandomOrder()->first()->id,  // Selects a random class
                'coach_id' => Coache::inRandomOrder()->first()->id,  // Selects a random coach
                'min_participants' => $this->faker->numberBetween(5, 10),  // Random number of minimum participants
                'max_participants' => $this->faker->numberBetween(15, 30),  // Random number of maximum participants
                'start_time' => $startTime,  // Random future start time
                'end_time' => $endTime,  // Ensures end time is later than start time
                'location' => $this->faker->randomElement(['Gym Hall', 'Studio 1', 'Studio 2', 'Outdoor Field']),  // Random location
            ];
       
    }
}
