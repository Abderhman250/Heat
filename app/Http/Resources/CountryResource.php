<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
  
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'name_ar' => $this->name_ar,
            'phone_code' => $this->phone_code,
            'region_id' => $this->region_id,
        ];
    }
}
