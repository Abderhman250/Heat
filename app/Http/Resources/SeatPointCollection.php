<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeatPointCollection extends ResourceCollection
{
 

    public function __construct($resource  )
    {
 
        parent::__construct($resource);
 
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $grouped = $this->collection->groupBy('line');
        // return $this->collection;
         $data =  $grouped->mapWithKeys(function ($points, $line) {
            // Format the data
         
            return $points->map(function ($point) {

                return [
                    'id' => $point->id,
                    'seat_number' => $point->seat_number,
                    'line' => $point->line,
                    'seat_not_active'=>$point->seat_not_active,
                    'class_id' => $point->class_id,
                    'note' => $point->note,
                    'is_booking' => $this->is_booking($point->id),
                ];
            });
        })->all();

        return $data;
    }

    private function is_booking($point_id)
    {
 
 
        $parameters =  Request()->route()->parameters();
        $appointment_id =  isset($parameters['appointment_id'])?$parameters['appointment_id']:null;
        return  Booking::where('appointment_id', $appointment_id)
            ->where('seat_id', $point_id)
            ->exists();
    }
}
