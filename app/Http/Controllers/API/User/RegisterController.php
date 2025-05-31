<?php

namespace App\Http\Controllers\API\User;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Interests;
use Illuminate\Support\Facades\Mail;

use App\Mail\OtpEmail;
use App\Models\Level;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {    
        $gender = ["male"=>2,'female'=>1];
        
 
        $request =  $request->validated();
        $level =  Level::where('required_classes',">",0)->first();
        
       $user = User::create([
            "email" =>   $request["email"],
            "username"=>$request["username"],
            "password" =>   bcrypt($request["password"]),
            "phone" => $request["phone"],  // Corrected the format of min and max rules
            'first_name' =>  $request["first_name"] ??  null,
            'last_name' =>  $request["last_name"]??  null,
            'gender' => $gender[$request["gender"]],
            'dob'=>$request["date_of_birth"]?? null,
            "level_id"=>$level->id,
 
        ]);
 

        $user->syncRoles(['user']);
        
       return ApiResponse::success([],"Registration completed successfully.",100,201);
    }
 
}
