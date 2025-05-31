<?php

namespace App\Http\Requests;

use App\Rules\EmailOrPhone;
use App\Rules\ValidUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginRequest extends FormRequest
{

    public function all($keys = null)
    {
        $data = parent::all($keys);
        // $parameters = Request()->route()->parameters();
        $route = $this->route()->getName();
        
        if ($route == "auth.login") {
            if (isset($data['email']))
                $data['login_method'] = 'email';

        }

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

        $route = $this->route()->getName();

        if ($route == "auth.login")
            return [
                "email" => ["nullable", "email", new ValidUser()],
 
                "password" => ["required"],
                "login_method" => ["required"],
                "device_token" => ["required"]
            ];
        elseif ($route == "auth.login.google")
            return [
                "email" => ["nullable", "email"],
                "givenName" => ["required", "string"],
                "familyName" => ["required", "string"],
                "google_id" => ["required"],
                'username' => ["required", 'min:3'],
                'photo' => ['required'],
    
            ];
        elseif ($route == "auth.login.facebook")
            return [
                "email" => ["nullable", "email"],
                "givenName" => ["required", "string"],
                "familyName" => ["required", "string"],
                "facebook_id" => ["required"],
                'username' => ["required", 'min:3'],
                'photo' => ['required'],
            ];

        elseif ($route == "auth.login.apple")
            return [
                "email" => ["nullable", "email"],
                "givenName" => ["required", "string"],
                "familyName" => ["required", "string"],
                "apple_id" => ["required"],
                'username' => ["required", 'min:3'],
                'photo' => ['required'],
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
