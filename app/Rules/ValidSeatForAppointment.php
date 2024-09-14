<?php

namespace App\Rules;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\SeatPoint;
use Illuminate\Contracts\Validation\Rule;

class ValidSeatForAppointment implements Rule
{
    protected $appointmentId;

    public function __construct($appointmentId)
    {
        $this->appointmentId = $appointmentId;
    }

    public function passes($attribute, $value)
    {
        $appointment = Appointment::find($this->appointmentId);
 
         if (!$appointment)
            return false;
 
         $seatPoint = SeatPoint::find($value);
 
 
        if (!$seatPoint || $this->isSeatAvailableForAppointment($seatPoint, $appointment)) {
             return false;
        }

        return true;
    }

    protected function isSeatAvailableForAppointment($seatPoint, $appointment)
    {
        
       if($seatPoint->class_id !== $appointment->class_id)
         return false;
 
        return  Booking::where('appointment_id',  $appointment->id)
        ->where('seat_id', $seatPoint->id)
        ->exists();
   
    }


  

    public function message()
    {
        return 'The selected seat is not valid for the specified appointment.';
    }
}
