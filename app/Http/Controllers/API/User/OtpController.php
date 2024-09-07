<?php

namespace App\Http\Controllers\API\User;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Requests\OtpRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpEmail;


class OtpController extends Controller
{

    public function verifyOtp(OtpRequest $request)
    {
        $request =  $request->validated();

        $user = User::where('email', $request['email'])->first();
        
        if (!$user) {
            return ApiResponse::error('User not found', Response::HTTP_NOT_FOUND);
        }
 
        if ($user->otp !== $request['otp']) {
            return ApiResponse::error('Invalid OTP', Response::HTTP_BAD_REQUEST);
        }

        $user->is_active = 1;
        $user->email_verified_at = Carbon::now();
        $user->otp = null;
        $user->save();

        return ApiResponse::success();
    }

    public function sendOtp(Request $request)
    {
 
        $user = User::where('email', $request['email'])->first();
        return ApiResponse::success($user);
        if (!$user) {
            return ApiResponse::error('User not found', Response::HTTP_NOT_FOUND);
        }

        $otp = User::generateUniqueOTP();

        $user->otp = $otp;
        $user->save();
        Mail::to($request['email'])->send(new OtpEmail($otp));
      
        return ApiResponse::success();

        // if (Mail::to($request['email'])->send(new OtpEmail($otp)))
        //     return ApiResponse::success();
        // else
        //     return ApiResponse::error('Email not send, please try again', Response::HTTP_BAD_REQUEST);
    }
 
}
