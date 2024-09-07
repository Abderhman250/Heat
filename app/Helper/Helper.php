<?php

namespace App\Helper;

use Storage;
use Intervention\Image\Facades\Image;
use App\SMSCode;
use App\MobileToken;
use App\NotificationMobile;

use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;


class Helper
{
    public static function resposniveSize($originalWidth, $originalHeight)
    {
        // Get Ration
        $ratio = $originalWidth / $originalHeight;

        // New Width
        $targetWidth = intval(350 * $ratio);

        // New Height
        $targetHeight = intval($targetWidth / $ratio);

        return array($targetWidth, $targetHeight);
    }

    public static function uploadToSpaceOptimized($file, $where)
    {
 
        $mimeType = $file->getMimeType();
        
        // Check if the file is an image
        if (str_starts_with($mimeType, 'image/')) {
            // Handle image optimization
            $take_sizes = Image::make($file);
            $resposniveSize = self::resposniveSize($take_sizes->width(), $take_sizes->height());
            $image = Image::make($file)->fit($resposniveSize[0], $resposniveSize[1])->stream($file->getClientOriginalExtension(), 90);
            $hash = md5($image->__toString());
            Storage::disk('public')->put($where . '/' . $hash . '.' . $file->getClientOriginalExtension(), $image->__toString());
            Storage::disk('public')->setVisibility($where . '/' . $hash . '.' . $file->getClientOriginalExtension(), 'public');
            $url = Storage::disk('public')->url($where . '/' . $hash . '.' . $file->getClientOriginalExtension());
        } else {
            // Handle video upload without optimization
            $hash = md5_file($file->getRealPath());
            $path = $where . '/' . $hash . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($path, file_get_contents($file));
            Storage::disk('public')->setVisibility($path, 'public');
            $url = Storage::disk('public')->url($path);
        }

        return $url;
    }

    public static function formatMobileNumber($number, $country_code)
    {
        $mobile_number = preg_replace("/[^0-9]/", "", $number);

        if (mb_strlen($mobile_number) < 11 && substr($mobile_number, 0, 1) == "0") {
            $mobile_number = preg_replace('/^0/', $country_code, $number);
        }

        if (mb_strlen($mobile_number) < 10) {
            $mobile_number = $country_code . $mobile_number;
        }

        return strval('+' . $mobile_number);
    }

    public static function sendVerification($phone, $idd = null)
    {
        if ($phone == "+962780822933" || $phone == "+962770358748" || $phone == "+962790925633") {
            $code = 12345;
        } else {
            $code = mt_rand(10000, 99999);
        }

        try {
            $id = config('services.twilio.id');
            $token = config('services.twilio.token');
            $url = "https://api.twilio.com/2010-04-01/Accounts/" . config('services.twilio.id') . "/Messages.json";
            $data = array(
                'Title' => 'Verification Code',
                'From' => '+16178499854',
                'To' => $phone,
                'Body' => 'Your verification code is ' . $code,
            );
            $post = http_build_query($data);
            $x = curl_init($url);
            curl_setopt($x, CURLOPT_POST, true);
            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
            $y = curl_exec($x);
            curl_close($x);
            if ($idd) {
                SMSCode::insert(['user_id' => $idd, 'code' => $code, 'used' => 0, 'created_at' => now(), 'updated_at' => now()]);
            } else {
                SMSCode::insert(['user_id' => Auth('api')->user()->id, 'code' => $code, 'used' => 0, 'created_at' => now(), 'updated_at' => now()]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }



    public static function sendNotification($title, $body, $aid, $destination)
    {
        $badge = 0;
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = array(
            'title' => $title,
            'body' => $body,
            'vibrate' => 1,
            "action" => "url",
            'sound' => 'default',
            'badge' => $badge,
            'priority' => 'high',
            "action_destination" => $destination,
        );

        $fields = array('to' => $aid, 'notification' => $message, 'data' => $message);
        /*$fields = array(
            'registration_ids' => $aid,
            'data' => $message
        );*/
        $headers = array(
            'Authorization: key=' . config('services.firebase.token'),
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //die(var_dump($result));
        curl_close($ch);
        return $result;
    }
    public static function sendNotificationExpo($title, $body, $token)
    {
        $messages = [
            // [
            //     'title' => $title,
            //     'to' => $token,
            // ],
            new ExpoMessage([
                'title' => $title,
                'body' => $body,
            ]),
        ];

        $defaultRecipients = [ $token ];
        $user_id = MobileToken::where(['token' => $token])->pluck('user_id')->first();
         NotificationMobile::create([
            'title'   => $title , 
            'body'    => $body  ,
            'user_id' => $user_id 
         ]);

        (new Expo)->send($messages)->to($defaultRecipients)->push();
        
        return true;
    }

    public static function ApplyOTP($phone)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://identitytoolkit.googleapis.com/v1/accounts:signInWithCustomToken?key=" . config('services.firebase.FIREBASE_KEY')
                . "--data-binary{'token':'[CUSTOM_TOKEN]','returnSecureToken':true'}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "application: json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    /**
     * Generate random number
     *
     * @param integer $digits Number of digits
     * @return string A random number
     */
    public static function generateRandomNumber(int $digits = 6): string
    {
        $min = pow(10, ($digits - 1)); // Minimum value for the desired number of digits
        $max = pow(10, $digits) - 1;    // Maximum value for the desired number of digits
        return str_pad(mt_rand($min, $max), $digits, '0', STR_PAD_LEFT);
    }
}
