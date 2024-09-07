<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Followers;

class UserBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         parent::toArray($request);
         return [
            'id'                 => $this['id'],
            'user_id'            => $this['user_id'],
            'blocked_user_id'    => $this['blocked_user_id'],
            'name'               => $this['blockedUser']['first_name'] . " " .  $this['blockedUser']['last_name'],
            'country_id'         => $this['blockedUser']['country_id'],
            'country_name'       => $this['blockedUser']['country']['name'],
            'country_name_ar'    => $this['blockedUser']['country']['name_ar'],
            'followers'          => Followers::where('user_id', $this['blocked_user_id'])->count() ?? 0 ,
         ];
    }
}
