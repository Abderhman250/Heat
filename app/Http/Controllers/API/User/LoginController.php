<?php

namespace App\Http\Controllers\API\User;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Constants;
use App\User;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;


class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        $request =  $request->validated();
        return  $this->loginByNameAndPassword($request);
    }

    public function loginByGoogle(LoginRequest $request)
    {
  
        // Validate request data
        $validatedData = $request->validated();

        // Prepare user data for login
        $data_login = [
            "email" => $validatedData["email"],
            'first_name' => $validatedData["givenName"],
            'last_name' => $validatedData["familyName"],
            'google_id' => $validatedData["google_id"],
            'username' =>$validatedData["username"],
            'photo' => $validatedData["photo"],
            'is_active' => true, 
        ];

        // Check if a user with the given Google ID exists
        $user = User::where('google_id', $validatedData["google_id"])->first();
 


        // Return user info
        return $this->getinfoUser($user);
    }

    public function loginByFacebook(LoginRequest $request)
    {
  

        // Validate request data
        $validatedData = $request->validated();

        // Prepare user data for login
        $data_login = [
            "email" => $validatedData["email"],
            'first_name' => $validatedData["givenName"],
            'last_name' => $validatedData["familyName"],
            'facebook_id' => $validatedData["facebook_id"],
            'username' =>$validatedData["username"],
            'photo' => $validatedData["photo"],
            'is_active' => true,
        ];

        // Check if a user with the given Google ID exists
        $user = User::where('facebook_id', $validatedData["facebook_id"])->first();
 
        if (!$user) {

            $data_login['completing_info'] = false;

            // Check if a user with the given email already exists
            if (User::where('email', $data_login['email'])->exists())
                return ApiResponse::error("You can't create an account with this email because it already exists. Please use another method to login.", Response::HTTP_UNAUTHORIZED);

            $user = User::create($data_login);
            
        } else {
           
            $user->update($data_login);
        }

        // Return user info
        return $this->getinfoUser($user);
    }


    public function loginByApple(LoginRequest $request)
    {
  

        // Validate request data
        $validatedData = $request->validated();

        // Prepare user data for login
        $data_login = [
            "email" => $validatedData["email"],
            'first_name' => $validatedData["givenName"],
            'last_name' => $validatedData["familyName"],
            'apple_id' => $validatedData["apple_id"],
            'username' =>$validatedData["username"],
            'photo' => $validatedData["photo"],
            'is_active' => true,
        ];

        // Check if a user with the given Google ID exists
        $user = User::where('apple_id', $validatedData["apple_id"])->first();
 


        // Return user info
        return $this->getinfoUser($user);
    }

    public function loginByNameAndPassword($request)
    {

        /** @var User $user */
        $loginMethod = $request["login_method"];
        if (Auth::attempt([$loginMethod => $request[$loginMethod], "password" => $request['password']])) {
            $user = Auth::user();
            return $this->getinfoUser($user);
        }

        return  ApiResponse::error("Login failed", Response::HTTP_UNAUTHORIZED);
    }




    public function getinfoUser($user)
    {

        if (!$user->is_active)
            return  response()->json(["error is not active"], 422);

        $token = $user->createToken('test');
        $access_token = $token->accessToken;

        $user_details = $user;

        return  ApiResponse::success(['user_details' => new  UserResource($user_details), "token" => $access_token, "user_type" => $user->user_type]);
    }

 
}
