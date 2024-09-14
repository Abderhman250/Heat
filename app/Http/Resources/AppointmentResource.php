<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\BookingClassUser;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $coach = $this->coach;
        $user = $coach->user; 
        return [
            'id' => $this->id,
            'appointment_name' => $this->appointment_name,
            'class' => [
                'id' => $this->class->id,
                'name' => $this->class->name,
                'room' => $this->class->room,
                'seat_selection_required' => $this->class->seat_selection_required,
            ],
            'coach' => [
                'id'=>$coach->id,
                "name" => $user->first_name ." ". $user->last_name,
                "profile_photo" => $coach->profile_photo,
            ],
            'min_participants' => $this->min_participants,
            'max_participants' => $this->max_participants,
            'start_time' => ($this->start_time !== null) ? $this->formatTime($this->start_time)  : null,
            'end_time' => ($this->end_time !== null) ? $this->formatTime($this->end_time)  : null,
            'time_class' => $this->timeClass(),
            'location' => $this->location,

            'is_reserve' => $this->isReserve(),
        ];
    }


    private function  isReserve()
    {

        if ($this->start_time < now())
            return false;

        $count = Booking::where('appointment_id', $this->id)->count();
  
        $user = auth()->user();

        if ($count >=  $this->max_participants)
            return false;


        return true;
    }


    private  function formatTime($time)
    {

        if ($time === null)
            return null;

        $carbonTime = Carbon::parse($time);

        return [
            "data_time" => $time,
            "time" => $carbonTime->format('h:i:s A'),
            "ago" => $carbonTime->diffForHumans(),
        ];
    }

    private function  timeClass()
    {

        $startTime = Carbon::parse($this->start_time);
        $finishTime = Carbon::parse($this->end_time);

        return  $finishTime->diffForHumans($startTime);
    }
}
