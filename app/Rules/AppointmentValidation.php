<?php

namespace App\Rules;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\BookingClassUser;
use Illuminate\Contracts\Validation\Rule;

class AppointmentValidation implements Rule
{
    protected $user;
 
    public function __construct($user )
    {
        $this->user = $user;
 
    }

    public function passes($attribute, $value)
    {
        $appointment = Appointment::find($value);
        
        if($appointment === null)
           return false;
        
        // Check if the appointment has already started
        if ($appointment->start_time < now()) {
            return false;
        }

        // Check if the maximum participant limit for the appointment has been reached
        $currentParticipants = Booking::where('appointment_id', $appointment->id)->count();
        if ($currentParticipants >= $appointment->max_participants) {
            return false;
        }

        // Check if the user has a valid booking for this class
        $bookingClassUser = BookingClassUser::where([
            'user_id' => $this->user->id,
            'class_id' => $appointment->class_id,
        ])->first();

        if (!$bookingClassUser || $bookingClassUser->quantity <= 0) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Validation failed for appointment.';
    }
}
