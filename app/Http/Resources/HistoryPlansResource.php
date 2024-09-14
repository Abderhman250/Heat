<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryPlansResource extends JsonResource
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
            "id"=> $this->id,
            "plan"=> [
                "plan_id"=>$this->plan->id,
                "plan_class"=>$this->plan->class->name,
                "plan_photo"=>$this->plan->photo,
                "class_type"=>$this->plan->class->classType->type_name,
                "class_capacity"=>$this->plan->class->capacity        
            ] ,
            "start_date"=>$this->start_date,
            "end_date"=>$this->end_date,
            "date_pay"=>$this->created_at,
        ];
    }
}
