<?php

namespace App\Http\Resources;

use App\Followers;
use App\GroupInvite;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowingInvaiteResource extends JsonResource
{
    public function toArray($request)
    {
        // dd($request->group_id);

        $date = ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null;
        $User = User::with(['country','invite'])->find($this->user_id);
        $Followers = Followers::where('user_id', $this->user_id);
        $followering = Followers::where('user_id', $this->follower_id);
        $group_id = (int) $request->group_id;
        $is_invite =  GroupInvite::where(['user_id'=>$this->follower_id,"group_id"=>$group_id])->exists();
 
        return [
            'id' => $this->id,  
            'user_id' => $this->follower_id,
            'created_at' => $this->created_at,
            'follower_at' => $date,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'username' => $this->username,
                'is_active' => $this->is_active,
                'photo' => $this->photo,
                'gender' => $User->gender ?? null,
                'country'=>$User->country->name?? null,
                'count'=> $Followers->count() ?? 0 ,
                'is_follow'=> $followering->where('follower_id',$this->user_id )->exists() ?? False ,
                'is_invite' =>$is_invite,
    
        ];
    }
}
