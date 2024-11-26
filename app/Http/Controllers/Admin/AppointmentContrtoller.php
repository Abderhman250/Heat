<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AppointmentContrtoller extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $appointments = Appointment::with('class', 'coach')->select('id', 'appointment_name', 'class_id', 'coach_id', 'min_participants', 'max_participants', 'start_time', 'end_time', 'location', 'created_at', 'updated_at');

            return DataTables::of($appointments)
                ->addColumn('class', function ($appointment) {
                    return  '<a href="' . route('admin.classes.index') . '">' . $appointment->class->name . '</a>';
                })
                ->addColumn('coach', function ($appointment) {
                    $coach =  $appointment->coach;
                    if ($coach !== null)
                        return '<a href="' . route('admin.coach.index') . '">' . $coach->user->first_name . ' ' . $coach->user->last_name . '</a>'; // Properly formatted anchor tag
                    else
                        return null;
                })

                ->addColumn('action', function ($class) {
                    return '
                                <form action="' . 1 . '" method="POST" style="display:inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-warning btn-md"> Action</button>
                            </form>';
                })
                ->rawColumns(['coach', 'class', 'action'])
                ->make(true);
        }

        return view('admin.appointment.index');
    }

    public function booking(Request $request)
    {

        if ($request->ajax()) {
            $Booking = Booking::with(['user:id,username', 'appointment:id,appointment_name', 'seat:id,seat_number'])->select('id','user_id', 'appointment_id', 'seat_id', 'status');

            return DataTables::of($Booking)
                ->addColumn('username', function ($Booking) {
                    return  '<a href="' . route('admin.coach.index') . '">' . $Booking->user->username . '</a>';
                })
                ->addColumn('appointment', function ($Booking) {
                    $appointment =  $Booking->appointment;
                    if ($appointment !== null)
                        return '<a href="' . route('admin.coach.index') . '">' . $appointment->appointment_name . ' ' . $appointment->appointment_name . '</a>'; // Properly formatted anchor tag
                    else
                        return null;
                })
                ->addColumn('seat', function ($Booking) {
                    $seat =  $Booking->seat;
                    if ($seat !== null)
                        return '<a href="' . route('admin.coach.index') . '">' . $seat->seat_number . ' ' . $seat->seat_number . '</a>'; // Properly formatted anchor tag
                    else
                        return null;
                })

          

                ->addColumn('status', function ($row) {
                    $status_case = [
                        "confirmed" => 'success',
                        "canceled" => 'danger'
                    ];
                    $status = $row->status;
                    $badgeClass = isset($status_case[$status]) ? $status_case[$status] : 'secondary'; 
                    return '<span class="badge badge-' . $badgeClass . '"> ' . ucfirst($status) . '</span>';
                })
                ->addColumn('action', function ($class) {
                    return '
                                <form action="' . 1 . '" method="POST" style="display:inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-warning btn-md"> Action</button>
                            </form>';
                })
                ->rawColumns(['username', 'appointment', 'seat','status','action']) // Specify columns  
                ->make(true);
        }

        return view('admin.appointment.booking.user');
    }






    public function seats(){

        return view('admin.appointment.seat.index');
    }
}
