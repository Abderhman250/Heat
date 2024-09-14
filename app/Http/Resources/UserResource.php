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
 
        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'username' => $this->username,
            "date_of_birth"=>$this->dob ,
            'is_active' => $this->is_active,
            'photo' => $this->photo,
            'gender' => $this->gender ,
            'email' => $this->email,
        ];
 

        return $data;
    }



 
}
