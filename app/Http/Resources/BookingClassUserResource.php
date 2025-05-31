<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingClassUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'class' => [
                'id' => $this->class->id,
                'name' => $this->class->name,
                'room' => $this->class->room,
                'seat_selection_required' => $this->class->seat_selection_required,
                'photo' => $this->class->photo,

            ],
            'quantity' => $this->quantity,
            'class_completed' => $this->class_completed,
        ];
    }
}
