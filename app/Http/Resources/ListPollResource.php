<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id, 
            'title' => $this->title,
            'photo' => $this->photo,
            'type_id' =>  $this->lookup($this->type),
            'area_id' => $this->lookup($this->area),
            'design_id' => $this->lookup($this->designs),
            'group_id' => $this->group_id,
            'is_draft' => (bool) $this->is_draft,
            'poll_link'=> $this->poll_link,
            'user' => [
                "id" => $this->user_id,
                "username" => $this->user->username,
                "photo" => $this->user->photo,
                
            ],
            'poll_ago' => ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null,
        ];
    }

    private function  lookup($data)
    {
        if ($data === null)
            return null;

        return [
            "id" => $data->id,
            "value" => $data->value,
            "description" => $data->description
        ];
    }

}
