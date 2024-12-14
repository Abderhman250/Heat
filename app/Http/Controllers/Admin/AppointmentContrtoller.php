<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\ClassModel;
use App\Models\Coache;
use App\Models\SeatPoint;
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
                    return  '<b class="text-warning">' . $appointment->class->name . '</b>';
             //                    return  '<b href="' . route('admin.classes.index') . '">' . $appointment->class->name . '</b>';

                })
                ->addColumn('coach', function ($appointment) {
                    $coach =  $appointment->coach;
                    return($coach !== null)?  $coach->user->first_name . ' ' . $coach->user->last_name : null;// Properly formatted anchor tag
 
                    //return '<a href="' . route('admin.coach.index') . '">' . $coach->user->first_name . ' ' . $coach->user->last_name . '</a>'; // Properly formatted anchor tag

                })

                ->addColumn('seat_selection', function ($appointment) {
                    $class =  $appointment->class;

                    if ($class->seat_selection_required == true) {
                        return '<a href="' . route('admin.seat.show', $appointment->id) . '" class="" style="text-decoration: none;">
                                    <i class="fas fa-chair"></i> Show Seats
                                </a>';
                    }
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
                ->rawColumns(['coach', 'seat_selection', 'class', 'action'])
                ->make(true);
        }

        return view('admin.appointment.index');
    }

    public function booking(Request $request)
    {

        if ($request->ajax()) {
            $Booking = Booking::with(['user:id,username', 'appointment:id,appointment_name', 'seat:id,seat_number'])->select('id', 'user_id', 'appointment_id', 'seat_id', 'status');

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
                ->rawColumns(['username', 'appointment', 'seat', 'status', 'action']) // Specify columns  
                ->make(true);
        }

        return view('admin.appointment.booking.user');
    }






    // public function seats(){


    //     return view('admin.appointment.seat.index');
    // }

    public function seats()
    {
        // Fetching seat data
        $seats = SeatPoint::all();  // Adjust based on your database structure

        // Pass the seat data to the view
        return view('admin.appointment.seat.index', compact('seats'));
    }


    public function show_seat(Request $request, $appointment_id)
    {
        $request['appointment_id'] = $appointment_id;
        $request->validate([
            'appointment_id' => 'required|integer|exists:appointments,id',
        ]);

        $appointmentId = $request->input('appointment_id');
        $appointment = Appointment::find($appointmentId);

        // Fetch all booked seat ids for the given appointment_id
        $bookedSeats = Booking::where('appointment_id', $appointmentId)
            ->pluck('seat_id')
            ->toArray();  // Efficiently return an array of booked seat IDs

        // Fetch all seats related to the class in a single query
        $seats = SeatPoint::where('class_id', $appointment->class_id)->get();

        // Return optimized view with necessary data
        return view('admin.appointment.seat.index', [
            'seatPoints' => $seats,
            'bookedSeats' => $bookedSeats,
            'appointmentId' => $appointmentId
        ]);
    }

    // public function searchSeatsByClass(Request $request)
    // {
    //     // Validate input
    //     $request->validate([
    //         'appointment_id' => 'required|integer|exists:appointments,id',
    //     ]);

    //     // Search using the scope
    //     $seatPoints = SeatPoint::searchByClass($request->input('class_id'))->get();

    //     return view('admin.appointment.seat.index', compact('seatPoints'));
    // }



    public function class_seat(Request $request)
    {

        // 'seat_number', 'seat_not_active', 'line', 'class_id', 'note's
        if ($request->ajax()) {
            $SeatPoint = SeatPoint::with('class:id,name,room') // Eager load the 'class' relationship
                ->select('id',  'seat_number', 'line', 'note', 'class_id');

            return DataTables::of($SeatPoint)
                ->addColumn('class', function ($seat) {
                    return  '<a href="' . route('admin.coach.index') . '">' . $seat->class->name . '</a>';
                })

                ->addColumn('room', function ($seat) {
                    return  '<a href="' . route('admin.coach.index') . '">' . $seat->class->room . '</a>';
                })
                ->rawColumns(['id', 'class', "room", 'seat_number', 'line', 'note']) // Specify columns  
                ->make(true);
        }

        return view('admin.appointment.seat.class_seat');
    }

    public function searchSeatsByClass(Request $request)
    {
        // Validate input
        $request->validate([
            'class_id' => 'required|integer|exists:appointments,id',
        ]);

        // Fetch the appointment details
        $appointmentId = $request->input('class_id');
        $appointment = Appointment::find($appointmentId);

        // Fetch all booked seat ids for the given appointment_id
        $bookedSeats = Booking::where('appointment_id', $appointmentId)
            ->pluck('seat_id')
            ->toArray();  // Efficiently return an array of booked seat IDs

        // Fetch all seats related to the class in a single query
        $seats = SeatPoint::where('class_id', $appointment->class_id)->get();

        // Return optimized view with necessary data
        return view('admin.appointment.seat.index', [
            'seatPoints' => $seats,
            'bookedSeats' => $bookedSeats,
            'appointmentId' => $appointmentId
        ]);
    }



    public function create()
    {
        try {
            $classes = ClassModel::select('id', 'name')->get();
            $coaches = Coache::select('id', 'username')->get();
            return view('admin.appointment.create', compact('classes', 'coaches'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_name' => 'required|string|max:255',
                'class_id'         => 'required|exists:classes,id',
                'coach_id'         => 'required|exists:coaches,id',
                'min_participants' => 'required|integer|min:1',
                'max_participants' => 'required|integer|gte:min_participants',
                'start_time'       => 'required|date|after:now',
                'end_time'         => 'required|date|after:start_time',
                'location'         => 'required|string|max:255',
            ]);

            Appointment::create($validated);

            return redirect()->route('admin.appointments.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }
}
