<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoacheResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return  [
            "id" => $this->id,
            "email" => $this->user->email,
            "phone" => $this->user->phone,
            "gender" => ($this->user->gender === false) ? "male" : "female",
            "name" => $this->user->first_name ." ". $this->user->last_name,
            'specialty' => $this->specialty,
            'email' => $this->email,
            'bio' => $this->bio,
            'profile_photo'  => $this->profile_photo,

        ];
        // return parent::toArray($request);
    }
}
