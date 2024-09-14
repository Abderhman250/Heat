<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoachRequest;
use App\Http\Resources\CoacheResource;
use App\Models\Coache;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 5);
        try {
            $coachs = Coache::  orderBy("created_at", 'desc')
            ->paginate($perPage);


            return ApiResponse::success(

                [
                    "collect" => CoacheResource::collection($coachs),
                    'pagination' => ApiResponse::paginationData($coachs),

                ],
                'Successfully list coach resource.',
                214
            );
        } catch (\Throwable $e) {
            // Handle potential exceptions
            return ApiResponse::error('Failed to fetch coaches. Please try again later.',500);
        }
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
    public function show(CoachRequest $request,$id)
    {
        $coachs = Coache::findOrFail($id);


        return ApiResponse::success(
             new  CoacheResource($coachs),
            'Successfully show coach resource.',
            215
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
