<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
class CoachRequest extends FormRequest
{
    public function all($keys = null)
    {
        $data = parent::all($keys);
 
        $parameters = Request()->route()->parameters();
        $route = $this->route()->getName();

        if ($route == "coach.show")
            $data['coach_id'] =  $parameters['coach_id'] ?? null;
        return  $data;
    }

    /**  coach
     * 
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
 
        $route = $this->route()->getName();

        if ($route == "coach.index")
            return [
             ];
        elseif ($route == "coach.show")
            return [
                "coach_id" => ['required', 'exists:coaches,id'],
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
