<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\BookingClassUser;
use App\Models\Level;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homePageData(Request $request)
    {
        $user = auth()->user(); // Assuming user is authenticated

        // 1. Registration Date
        $registrationDate = $user->created_at;

        // 2. Number of coaches user has requested appointments from
        $coachCount = Booking::where('user_id', $user->id)
            ->with('appointment')
            ->get()
            ->pluck('appointment.coach_id')
            ->unique()
            ->count();


        // 3. Most classes the user has taken
        $BookingClassUser = BookingClassUser::with('class')
            ->where('user_id', $user->id)
            ->orderBy('class_completed', 'DESC');

        $classAttended = $BookingClassUser->count('class_completed');
        $mostVisitedClass = $BookingClassUser->first();

        $level =  Level::where('required_classes', '>=', $classAttended)->first();

        return ApiResponse::success(

            [
                'level' => $level->title_levels,
                'registration_date' => $registrationDate,
                'coach_count' => $coachCount,
                'class_attended' => isset($classAttended) ? $classAttended  : 0,
                'most_visited_class' => isset($mostVisitedClass->class->name) ? $mostVisitedClass->class->name : null,
            ],
            'Successfully list hoem page.',
            113
        );
        return response()->json();
    }
}
