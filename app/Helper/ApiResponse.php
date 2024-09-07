<?php

namespace App\Helpers;
use Storage;
use Intervention\Image\Facades\Image;
class ApiResponse
{
    public static function success($data = [], $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true  ,
            'responseCode' => 1596  ,
            'message' => $message  ,
            'data' => $data  ,
        ], $status);
    }

    public static function error($message, $status = 400 ,$errors ="")
    {
        return response()->json([
            'success' => false,
            'responseCode' => 1696  ,
            'message' => $message,
            'data'=>[],
            'validator' => $errors
        ], $status);
    }
}