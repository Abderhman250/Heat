<?php

namespace App\Http\Resources;

use App\Followers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FollowersByUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null;
        $User = User::with('country')->find($this->follower_id);
        $Followers = Followers::where('user_id', Auth::id());

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'follower_at' => $date,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'is_active' => $this->is_active,
            'photo' => $this->photo,
            'gender' => $User->gender ?? null,
            'country' => $User->country->name ?? null,
            'count' => $Followers->count() ?? 0,
            'is_follow' => $Followers->where('follower_id', $this->user_id)->exists() ?? False,
        ];
    }
}
