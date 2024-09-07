<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Interests;
use Illuminate\Support\Facades\Mail;

use App\Mail\OtpEmail;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {    
        
        $request =  $request->validated();

        $gender = ["male"=>2,'female'=>1];

        $otp = User::generateUniqueOTP();

        $data=  User::create([
            "email" =>   $request["email"],
            "username"=>$request["username"],
            "password" =>   bcrypt($request["password"]),
            "phone" => $request["phone"],  // Corrected the format of min and max rules
            'first_name' =>  $request["first_name"] ??  null,
            'last_name' =>  $request["last_name"]??  null,
            'gender' => $gender[$request["gender"]],
 
            'country_id'=>$request["country_id"],
            'dob'=>$request["date_of_birth"]?? null,
            'city_id'=>$request["city_id"],
 
        ]);

        Mail::to($request['email'])->send(new OtpEmail($otp));

        $user=  User::findOrFail($data['id']);
 
        $intrest = collect($request['interests']?? []);
  
        $user->intrest()->sync( $intrest );

       return ApiResponse::success([]);
    }
 
}
