<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Country;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function country(Request $request)
    {
 
        $country = Country:: all();
        return ApiResponse::success(CountryResource::collection($country));
    }

    public function city(Request $request,$id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:countries,id',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) 
            return ApiResponse::error('Invalid country ID', 422, $validator->errors());
 
        $perPage = $request->query('per_page', 50);
        $city = City::where("country_id",$id)->paginate($perPage);
        return ApiResponse::success(CityResource::collection($city));
    }

 
}
