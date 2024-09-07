<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ListMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user_id =  Auth::id();

        return [
            "id"=>$this->id,
            "username"=>$this->username,
            "photo"=>$this->photo,
            "last_message"=>$this->last_message,
            "is_read"=>$this->is_read,
            "message_time"=>$this->message_time,
            "message_time_ago"=> ($this->message_time !== null) ? Carbon::parse($this->message_time)->diffForHumans() : null
        ];
    }
}
