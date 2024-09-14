<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\ClassModel;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 5);
        $plans = ClassModel::with('plans')
        ->has('plans')
        ->paginate($perPage);
 
        return ApiResponse::success(
            [
                "collect" => PlanResource::collection($plans),
                'pagination' => ApiResponse::paginationData($plans),

            ],
            'Successfully list coach resource.',
            314
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$id)
    {
        $perPage = (int) $request->query('per_page', 5);
        $plans = ClassModel::with('plans')
        ->has('plans')
        ->paginate($perPage);
 
        return ApiResponse::success(
            [
                "collect" => PlanShowResource::collection($plans),
                'pagination' => ApiResponse::paginationData($plans),

            ],
            'Successfully list coach resource.',
            314
        );
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
}