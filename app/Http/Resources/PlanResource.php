<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

class PlanResource extends JsonResource
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
           
            "class_id"=> $this->id,
            "name_class"=>$this->name,
            "class_type"=>$this->classType->type_name,
            "plans"=>$this->plans
 
        ];
    }
}
