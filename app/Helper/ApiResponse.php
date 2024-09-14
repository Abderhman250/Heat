<?php

namespace App\Helpers;
use Storage;
use Intervention\Image\Facades\Image;
class ApiResponse
{
    public static function success($data = [], $message = 'Success',$code=1596, $status = 200 )
    {
        return response()->json([
            'success' => true  ,
            'responseCode' => $code  ,
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


    public static function paginationData($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
        ];
    }

}