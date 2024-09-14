<?php

namespace App\Http\Resources;

use App\Lookup;
use Illuminate\Http\Resources\Json\JsonResource;

class LookupUserTypeResource extends JsonResource
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

            'id'=>$this->id,
            'value'=>$this->value,
            'description'=>$this->description,
            'options'=> LookupResource::collection(Lookup::where('referans_key', $this->key)->get())
        ];
    }
}
