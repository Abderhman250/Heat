<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class PlanRequest extends FormRequest
{

    public function all($keys = null)
    {
        $data = parent::all($keys);
 
        $parameters = Request()->route()->parameters();
        $route = $this->route()->getName();

        if ($route == "plan.show")
            $data['plan_id'] =  $parameters['plan_id'] ?? null;
        return  $data;
    }

    public function rules()
    {
 
        $route = $this->route()->getName();

        if ($route == "plan.index")
            return [
             ];
        elseif ($route == "plan.show")
            return [
                "plan_id" => ['required', 'exists:plans,id'],
            ];
        elseif ($route == "plan.histor")
            return [
           
            ];
           
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(

            ApiResponse::error("Unprocessable entity", Response::HTTP_UNPROCESSABLE_ENTITY, $errors)

        );
    }
}
