<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

class PlanShowResource extends JsonResource
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
           
            "class_id"=> $this->class->id,
            "name_class"=>$this->class->name,
            "class_type"=>$this->class->classType->type_name ,
            "plan"=> [
                 "id" =>$this->id,
                 "total_classes"=>$this->total_classes,
                 "description" =>$this->description,
                 "photo"=>$this->photo,
            ]
 
        ];
    }
}
