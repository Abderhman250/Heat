<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hash;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use App\Rules\CityBelongsToCountry;
use App\Rules\NotBlocked;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRequest extends FormRequest
{


    public function all($keys = null)
    {
        $data = parent::all($keys);
        // $parameters = Request()->route()->parameters();
        $route = $this->route()->getName();
        if ($route == "info.by.user")
            $data['user_id'] =  $parameters['user_id'] ?? null;
        return $data;
    }

    /**
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
        $userPhone = Rule::unique('users', 'phone')->whereNull('deleted_at');
        $users = Rule::exists('users', 'id')->whereNull('deleted_at');
        $userName = Rule::exists('users', 'username')->whereNull('deleted_at');
        $gender = ["male", 'female'];
        $route = $this->route()->getName();


        if ($route  === 'change.password')
            return [
                "old_password" =>  ["required",     function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, auth()->user()->password)) {
                        return $fail(__('The old password is incorrect.'));
                    }
                },],
                "password" =>  ["required", 'string', 'min:6', 'max:12'],
            ];


        elseif ($route === "user.forgetPassword")
            return [
                'email' => ['required', 'email',  "exists:users,email"],
            ];
        elseif ($route === "user.checkOTP")
            return [
                'email' => ['required', 'email', "exists:users,email"],
                'otp'   => ["required", 'string']
            ];
        elseif ($route === "user.resetPassword")
            return [
                'email' => ['required', 'email', "exists:users,email"],
                "password" =>  ["required", 'string', 'min:6', 'max:12'],
            ];
        elseif ($route === "user.setMobileToken")
            return  [
                'mobile_token' => ['required']
            ];

        elseif ($route  === 'user.edit.profile') {
        
            return [
                "phone" => ["required", Rule::unique('users', 'phone')->whereNull('deleted_at')->ignore(auth()->user()->id)],  // Corrected the format of min and max rules
                'first_name' => ['required', 'regex:/^[a-zA-Zء-ي_ -]+$/u'],
                'last_name' => ['required', 'regex:/^[a-zA-Zء-ي_ -]+$/u'],
                'photo' => ['image', 'mimes:jpeg,png,jpg,gif' ],
                'gender' => ['required',Rule::in($gender)],
            ];
        }
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(
            ApiResponse::error("Unprocessable entity", Response::HTTP_UNPROCESSABLE_ENTITY, $errors)
        );
    }
}
