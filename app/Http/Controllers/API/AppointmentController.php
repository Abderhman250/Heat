<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentShowResource;
use App\Http\Resources\SeatPointCollection;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\SeatPoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Group;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AppointmentRequest $request)
    {
        $perPage = (int) $request->query('per_page', 5);
        $date =  Carbon::parse($request->date);
        $start = $date->format('Y-m-d H:i:s');
        $end = $date->endOfDay()->format('Y-m-d H:i:s');

        $appointments = Appointment::where('start_time', '>', $start)
            ->where('start_time', '<', $end)
            ->orderBy('start_time', 'asc')
            ->paginate($perPage);


        return ApiResponse::success(

            [
                "collect" => AppointmentResource::collection($appointments),
                'pagination' => ApiResponse::paginationData($appointments),

            ],
            'Successfully list appointment resource.',
            112
        );

        return;
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
    public function show(AppointmentRequest $request, $id)
    {
        // 
        $appointments = Appointment::findOrFail($id);

        return ApiResponse::success(
            new  AppointmentShowResource($appointments),
            'Successfully show appointment resource.',
            113
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function isReserve(AppointmentRequest $request, $id)
    {
        //
        $appointments = Appointment::findOrFail($id);

        $is_reserve = true;

        if ($appointments->start_time < now())
            false;

        $count = Booking::where('appointment_id', $id)->count();

        if ($count >=  $appointments->max_participants)
            return false;

        return ApiResponse::success(
            ["is_reserve" => $is_reserve],
            'Successfully response is reserve.',
            116
        );
        
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seatPoint(AppointmentRequest $request, $id)
    {
        //
        $perPage = (int) $request->query('per_page', 5);

        $appointments = Appointment::findOrFail($id);

        $seatPoints = SeatPoint::where('class_id', $appointments->class_id)

            ->paginate($perPage);

        return ApiResponse::success(
            [
                "collect" => SeatPointCollection::collection($seatPoints->groupBy('line')),
                'pagination' => ApiResponse::paginationData($seatPoints),

            ],
            'Successfully list appointment resource.',
            118
        );

        return;
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
