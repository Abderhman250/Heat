<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\BookingClassUser;
use App\Models\ClassModel;
use App\Models\SeatPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'appointment_id' => Appointment::factory(),
            'seat_id' => $this->faker->optional()->randomElement(SeatPoint::pluck('id')->toArray()),  // Optional seat
            'status' => $this->faker->randomElement(['confirmed', 'pending', 'canceled']),
        ];
    }
}
