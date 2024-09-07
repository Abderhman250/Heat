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

            if (isset($data['phone']))
                $data['login_method'] = 'phone';

            if (isset($data['username']))
                $data['login_method'] = 'username';
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
                "email" => ["nullable", "email", new EmailOrPhone('phone'), new ValidUser()],
                "username" => ["nullable", "string", new ValidUser()],
                "phone" => ["nullable", new ValidUser()],  // Corrected the format of min and max rules
                "password" => ["required"],
                "login_method" => ["required"],
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
