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
        $plans = $this->plans ?? null;
        $plan = $this->plans->first() ?? null;  
        $class = $plan->class ?? null;
        $classType = $class->classType ?? null;
        
        return [
            "class_id" => $class->id ?? null,
            "name_class" => $class->name ?? null,
            "class_type" => $classType->type_name ?? null,
            'section_name' => $this->section_name,
            'description' => $this->description ?? $this->section_name, // Assuming description should come from the model's 'description' attribute, or fallback to section_name.
            "plans" => $plans
        ];
    }
}
