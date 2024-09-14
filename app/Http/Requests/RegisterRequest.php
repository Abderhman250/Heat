<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use App\Rules\CityBelongsToCountry;
use Symfony\Component\HttpFoundation\Response;
class RegisterRequest extends FormRequest
{
    public function all($keys = null)
    {
        $data = parent::all($keys);
        // $parameters = Request()->route()->parameters();
        $route = $this->route()->getName();
        // if($route == "auth.login")
        //    $data['login_method'] = isset($data['email']) ? 'email' : 'phone';
        return $data;
    }

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
        $userEmail = Rule::unique('users', 'email')->whereNull('deleted_at');
        $userPhone = Rule::unique('users', 'phone')->whereNull('deleted_at');
        $userName = Rule::unique('users', 'username')->whereNull('deleted_at');

        $gender = ["male",'female'];
 
        return [
            "email" => ["required", "email", $userEmail],
            'username'=>["required",$userName,'min:3'],
            "password" => ["required",'string','min:6','max:12'],
            'password_confirmation'=>["required","same:password"],
            "phone" => ["required" ,$userPhone],  // Corrected the format of min and max rules
            'gender' => ['required',Rule::in($gender)],
            'first_name' => ["required",'regex:/^[a-zA-Zء-ي_ -]+$/u'],
            'last_name' => ["required",'regex:/^[a-zA-Zء-ي_ -]+$/u'],
            'date_of_birth'=>['nullable','date'],
         ];
 
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(  ApiResponse::error("Unprocessable entity", Response::HTTP_UNPROCESSABLE_ENTITY,$errors)
    );
    }
}
