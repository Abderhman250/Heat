<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class bookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $appointment =  $this->appointment;
     
        $coach = $appointment->coach;

        $user = $coach->user;


        return [
            "id" => $this->id,
            "status" => $this->status,
            "appointment" => [
                'id' => $appointment->id,
                'appointment_name' => $appointment->appointment_name,
                'class' => [
                    'id' => $appointment->class->id,
                    'name' => $appointment->class->name,
                    'room' => $appointment->class->room,
                    'photo'=>$appointment->class->photo,
                    'seat_selection_required' => $appointment->class->seat_selection_required,
                ],
                'coach' => [
                    'id' => $coach->id,
                    "name" => $user->first_name . " " . $user->last_name,
                    "profile_photo" => $coach->profile_photo,
                ],

                'start_time' => ($appointment->start_time !== null) ? $this->formatTime($appointment->start_time)  : null,
                'end_time' => ($appointment->end_time !== null) ? $this->formatTime($appointment->end_time)  : null,
                'time_class' => $this->timeClass($appointment),
                'location' => $appointment->location,
            ]

        ];
    }

    private  function formatTime($time)
    {

        if ($time === null)
            return null;

        $carbonTime = Carbon::parse($time);

        return [
            "data_time" => $time,
            "formatted_time" =>  Carbon::parse($time)->format('h.ia l, M j'),
            "time" => $carbonTime->format('h:i:s A'),
            "ago" => $carbonTime->diffForHumans(),
        ];
    }

    private function  timeClass($appointment)
    {

        $startTime = Carbon::parse($this->start_time);
        $finishTime = Carbon::parse($this->end_time);
        
        // Calculate the difference in minutes
        return $startTime->diffInMinutes($finishTime);
    }
}
