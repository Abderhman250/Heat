<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $date = ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null;
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo,
            'owner_id' => $this->owner_id,
            'member_count' => $this->userGroups()->count(),  // Count members using the relationship method from the Group model
            'created_ago' => $date,
            'created_at' => $this->created_at,
        ];
        if ($request->route()->getName() === 'groups.list_accept')
            $data["invite_ago"] = ($this->date_invite !== null) ? Carbon::parse($this->date_invite)->diffForHumans() : null;
        return $data;
    }
}
