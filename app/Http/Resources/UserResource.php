<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Followers;
use Illuminate\Support\Facades\Auth;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $route = $request->route()->getName();

        $intrests = [];
        foreach ($this->intrest ?? [] as $intrest) {
            $intrests[] =  [
                'id' => $intrest->id,
                'name' => $intrest->name
            ];
        }
        $Followers =  Followers::where('follower_id', $this->id) ?? collect([]);
       
        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'username' => $this->username,
            "date_of_birth"=>$this->dob ,
            'country_code' => $this->country_code,
            'is_active' => $this->is_active,
            'org_name'=>$this->org_name,
            'photo' => $this->photo,
            'country' => [  "id" =>  $this->country->id ?? null,"name"=>$this->country->name ?? null],
            'city' => [  "id" =>  $this->city->id ?? null,"name"=>$this->city->name ?? null],
            'gender' => $this->gender ,
            'email' => $this->email,
            'followers_count'=> $Followers->count() ?? 0 ,
            'intrest' => $intrests,
            "completing_info" => (bool) $this->completing_info,
        ];

        if($route === "info.by.user") 
          $data["is_follow"]= $this->getIsFollower();

        return $data;
    }



    private function  getIsFollower()
    {

        return Followers::where('user_id', Auth::id())
            ->where('follower_id', $this->id)
            ->exists() ?? False;
    }
}
