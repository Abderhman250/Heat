<?php

namespace App\Rules;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\SeatPoint;
use Illuminate\Contracts\Validation\Rule;

class ValidSeatForAppointment implements Rule
{
    protected $seatPointId;

    public function __construct($seatPointId)
    {
        $this->seatPointId = $seatPointId;
    }

    public function passes($attribute, $value)
    {
        $appointment = Appointment::find($value);

        if (!$appointment)
            return false;

        if ($this->isSeatAvailableForAppointment($appointment)) {
            return false;
        }

        return true;
    }

    protected function isSeatAvailableForAppointment($appointment)
    {

        if ($appointment->class->seat_selection_required) {
            return  !Booking::where('appointment_id',  $appointment->id)
                ->exists();
        }

        $seatPoint = SeatPoint::find($this->seatPointId);

        if ($seatPoint->class_id !== $appointment->class_id)
            return false;


        return  !Booking::where('appointment_id',  $appointment->id)
            ->where('seat_id', $seatPoint->id)
            ->exists();
    }




    public function message()
    {
        return 'The selected seat is not valid for the specified appointment.';
    }
}
