<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentShowResource;
use App\Http\Resources\bookingResource;
use App\Http\Resources\SeatPointCollection;
use App\Lookup;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\BookingClassUser;
use App\Models\SeatPoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\TextUI\XmlConfiguration\Group;

class AppointmentController extends Controller
{


    public function filters()
    {

        $Lookup =  Lookup::select('id', 'type', 'key', 'value', 'description')
            ->where('type', "=", 'FILTER')
            ->get();

        return ApiResponse::success(
            [
                "filter" =>  $Lookup
            ],
            'Successfully listed dates.',
            110
        );
    }

    public function date(Request $request)
    {
        $start = Carbon::now();

        if ($request->has('start_date') && Carbon::hasFormat($request->start_date, 'Y-m-d')) {
            $start = Carbon::parse($request->start_date);
        }

        $end = $start->copy()->addDays(7);
        $dates = [];

        while ($start->lte($end)) {
            $dates[] = $start->toDateString();
            $start->addDay();
        }

        return ApiResponse::success(
            [
                "date" => $dates
            ],
            'Successfully listed dates.',
            110
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AppointmentRequest $request)
    {
        $morning =  Lookup::where('key', '=', 'FILTER_MORNING')->first();
        $afternoon =  Lookup::where('key', '=', 'FILTER_AFTERNOON')->first();
        $evening =  Lookup::where('key', '=', 'FILTER_EVENING')->first();

        $perPage = (int) $request->query('per_page', 5);
        $date = Carbon::parse($request->date);

        $startOfDay = $date->startOfDay()->format('Y-m-d H:i:s');
        $endOfDay = $date->endOfDay()->format('Y-m-d H:i:s');

        // Initialize the query for appointments
        $appointments = Appointment::where('start_time', '>', $startOfDay)
            ->where('start_time', '<', $endOfDay);

        // Filter based on time of day



        if ($request->has('filter_id'))
            switch ($request->filter_id) {
                case $morning->id:
                    $appointments->whereBetween('start_time', [(clone $date)->setTime(5, 0), (clone $date)->setTime(11, 0)]);
                    break;
                case $afternoon->id:
                    $appointments->whereBetween('start_time', [(clone $date)->setTime(11, 0), (clone $date)->setTime(15, 0)]);
                    break;
                case $evening->id:
                    $appointments->whereBetween('start_time', [(clone $date)->setTime(15, 0), $endOfDay]);
                    break;
            }

        // Order and paginate the appointments
        $appointments = $appointments->orderBy('start_time', 'asc')->paginate($perPage);

        return ApiResponse::success(
            [
                "collect" => AppointmentResource::collection($appointments),
                'pagination' => ApiResponse::paginationData($appointments),
            ],
            'Successfully listed appointment resources.',
            112
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coach(AppointmentRequest $request, $coach_id)
    {
        $perPage = (int) $request->query('per_page', 5);
        $date =  Carbon::parse($request->date);
        $start = $date->startOfDay()->format('Y-m-d H:i:s');
        $end = $date->endOfDay()->format('Y-m-d H:i:s');

        $appointments = Appointment::where('start_time', '>', $start)
            ->where('start_time', '<', $end)
            ->where('coach_id', '=', $coach_id);


        $appointments =   $appointments->orderBy('start_time', 'asc')->paginate($perPage);

        return ApiResponse::success(

            [
                "collect" => AppointmentResource::collection($appointments),
                'pagination' => ApiResponse::paginationData($appointments),

            ],
            'Successfully list appointment resource.',
            126
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
        $appointment = Appointment::findOrFail($id);


        // Check if the appointment has already started
        if ($appointment->start_time < now()) {
            return ApiResponse::success(
                ['is_reserve' => false],
                'The appointment has already started.',
                120
            );
        }

        // Check if the user has a valid booking for this class
        $bookingClassUser = BookingClassUser::where([
            'user_id' => auth()->id(),
            'class_id' => $appointment->class_id,
        ])->first();

        // If no valid booking or no quantity left
        if (!$bookingClassUser || $bookingClassUser->quantity <= 0) {
            return ApiResponse::success(
                ['is_reserve' => false],
                'No valid booking found or quantity exhausted.',
                121
            );
        }

        // Check if the maximum participant limit for the appointment has been reached
        $currentParticipants = Booking::where('appointment_id', $id)->count();
        if ($currentParticipants >= $appointment->max_participants) {
            return ApiResponse::success(
                ['is_reserve' => false],
                'The appointment has reached its maximum number of participants.',
                122
            );
        }

        // If all checks pass, the appointment can be reserved
        return ApiResponse::success(
            ['is_reserve' => true],
            'The appointment is available for reservation.',
            123
        );
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

        $data = [];
        $SeatPointCollection =  SeatPointCollection::collection($seatPoints->groupBy('line'));

        foreach ($SeatPointCollection as $key => $value)
            $data[][$key] =   $value;


        return ApiResponse::success(
            [
                "collect" => $data,
                'pagination' => ApiResponse::paginationData($seatPoints),

            ],
            'Successfully list appointment resource.',
            118
        );

        return;
    }

    public function reserve(AppointmentRequest $request)
    {
        $seat_point_id = null;
        $user = auth()->user();
        $appointment = Appointment::findOrFail($request->appointment_id);
        if (($appointment->class->seat_selection_required === true))
            $seat_point_id =  $request->seat_point_id;

        DB::beginTransaction();

        try {

            Booking::create([
                'appointment_id' => $request->appointment_id,
                'seat_id' => $seat_point_id,
                'user_id' => $user->id,
                'status' => 'confirmed',
            ]);
            $bookingClassUser = BookingClassUser::where([
                'class_id' => $appointment->class->id,
                'user_id' => $user->id,
            ]);

            if ($bookingClassUser) {
                $bookingClassUser->decrement('quantity', 1);
                $bookingClassUser->increment('class_completed');
            }


            DB::commit();

            return ApiResponse::success([], 'Successfully reservation.', 133);
        } catch (\Throwable $e) {
            DB::rollback();

            Log::error('Failed to create booking: ' . $e->getMessage());

            return response()->json(['error' => 'Booking failed, please try again later.'], 500);
        }
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
