<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;

use App\Models\User;
use App\MobileToken;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper\Helper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\OtpEmail;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class UserController extends Controller
{

    public function updateNotification()
    {
        $user = User::find(Auth::id());
        $user->enable_notification = !$user->enable_notification;
        $user->save();
        return ApiResponse::success([], "Successfully update status of notification");

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        //
        $user_id =  Auth::id();
        $user = User::with(['bookings','BookingClassUser'])->find($user_id);
        return ApiResponse::success(new UserResource($user), "Successfully retrieved user data",101,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(UserRequest $request)
    {
        User::where("id", Auth::id())->update(["password" =>   bcrypt($request["password"])]);

        return ApiResponse::success([], "Successfully update password");
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(UserRequest $request)
    {

        $user = Auth::user();
        $userId = $user->id;
        $userData = [];
        $gender = ["male"=>2,'female'=>1];
        $photo =null;
        if ($request->hasFile('photo')) 
            $photo = Helper::uploadToSpaceOptimized($request->file('photo'), 'app_content');
 
      
        $request =  $request->validated();
 
        $userData = [
            'phone' => $request['phone'],
            'first_name' =>  $request['first_name'],
            'last_name' => $request['last_name'],
            'dob' => $request['date_of_birth'] ?? null,
            'gender'=>$gender[$request['gender']],
            'photo'=>$photo
        ];
 
        User::where('id', $userId)->update($userData);

        $user = User::findOrFail($userId);

        return ApiResponse::success(new UserResource($user), "Successfully updated profile");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function forgetPassword(UserRequest $request)
    {
        DB::beginTransaction();
        try {

            $otp = User::generateUniqueOTP();
            User::where('email', $request->email)->update(['otp' => $otp]);
            Mail::to($request['email'])->send(new OtpEmail($otp));
            DB::commit();

            return ApiResponse::success([], 'Successfully send email for reset password is : ' . $request->email);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to send email', $e->getMessage());
        }
    }

    public function checkOTP(UserRequest $request)
    {

        $user = User::where('email', $request['email'])->first();

        if (!$user)
            return ApiResponse::error('User not found', Response::HTTP_NOT_FOUND);

        if ($user->otp !== $request['otp'])
            return ApiResponse::error('Invalid OTP', Response::HTTP_BAD_REQUEST);

        $user->is_active = 1;
        $user->email_verified_at = Carbon::now();
        $user->otp = null;
        $user->save();

        return ApiResponse::success([], 'Successfully check OTP for email : ' . $request->email);
    }


    public function resetPassword(UserRequest $request)
    {
        try {
            User::where('email', $request['email'])->update([
                'password' => bcrypt($request["password"])
            ]);

            return ApiResponse::success([], 'Successfully reset Password for email : ' . $request->email);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to send email', $e->getMessage());
        }
    }



    public function mobileToken()
    {
        $user_id =  Auth::id();
        $user    = User::with(['token'])->find($user_id);
        return ApiResponse::success($user['token']['token'], "Successfully mobile token for user");
    }



    public function setMobileToken(UserRequest $request)
    {

        MobileToken::where('user_id', Auth::id())->UpdateOrCreate([
            'user_id'   => Auth::id(),
            'token'     => $request->mobile_token
        ]);
        $user = User::with(['token'])->find(Auth::id());

        return ApiResponse::success($user, "Successfully set mobile token for user");
    }



    public function sendNotification(Request $request)
    {
        $user_id =  $request->user_id;
        $user = User::with(['token'])->findOrFail($user_id);
        if (isset($user['token']['token']))
            Helper::sendNotificationExpo($request->title, $request->body, $user['token']['token']);

        return ApiResponse::success([], "Successfully send notification for user");
    }

    public function infoByUser(UserRequest $request, $user_id)
    {
        //
        $user = User::find($user_id);

        return ApiResponse::success(new UserResource($user), "Successfully retrieved user data");
    }
}
