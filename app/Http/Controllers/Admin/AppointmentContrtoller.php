<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\ClassModel;
use App\Models\Coache;
use App\Models\SeatPoint;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AppointmentContrtoller extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $appointments = Appointment::with('class', 'coach')->select('id', 'appointment_name', 'class_id', 'coach_id', 'min_participants', 'max_participants', 'start_time', 'end_time', 'location', 'created_at', 'updated_at');
// dd($appointment);
            return DataTables::of($appointments)
                ->addColumn('class', function ($appointment) {
                    return  '<b class="text-warning">' . $appointment->class->name . '</b>';
                    //                    return  '<b href="' . route('admin.classes.index') . '">' . $appointment->class->name . '</b>';

                })
                ->addColumn('coach', function ($appointment) {
                    $coach =  $appointment->coach;
                    return ($coach !== null) ?  $coach->user->first_name . ' ' . $coach->user->last_name : null; // Properly formatted anchor tag

                })

                ->addColumn('seat_selection', function ($appointment) {
                    $class =  $appointment->class;

                    if ($class->seat_selection_required == true) {
                        return '<a href="' . route('admin.seat.show', $appointment->id) . '" class="" style="text-decoration: none;">
                                    <i class="fas fa-chair"></i> Show Seats
                                </a>';
                    }
                    return "-";
                })
                ->addColumn('duration',function($appointment){

                    $start_time = Carbon::parse($appointment->start_time);
                    $end_time = Carbon::parse($appointment->end_time);
                    
                    // Calculate the difference in minutes
                    return $start_time->diffInMinutes($end_time);
                })
                ->addColumn('action', function ($appointment) {
                    return '<a href="' . route('admin.appointments.edit', $appointment->id) . '" 
                                class="btn btn-sm btn-block btn-outline-info btn-xs">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>';
                })
           
                ->rawColumns(['coach', 'seat_selection', 'class', 'action','duration'])
                ->make(true);
        }

        return view('admin.appointment.index');
    }

    public function booking(Request $request)
    {

        if ($request->ajax()) {
            $Booking = Booking::with(['user:id,username', 'appointment.class', 'seat:id,seat_number',])->select('id', 'user_id', 'appointment_id', 'seat_id', 'status','created_at');
            
            if ($request->has('class') && $request->class != '') {
                $Booking->whereHas('appointment.class', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->class . '%');
                });
            }
    
            // Apply date filter if provided
            if ($request->has('date') && $request->date != '') {
                $Booking->whereHas('appointment', function ($query) use ($request) {
                    $query->whereDate('start_time', '=', Carbon::parse($request->date)->toDateString());
                });
            }
            return DataTables::of($Booking)
                ->addColumn('username', function ($Booking) {
                    return   $Booking->user->username;
                })
                ->addColumn('appointment', function ($Booking) {
                    $appointment =  $Booking->appointment;
                    if ($appointment !== null)
                        return    $appointment->appointment_name; // Properly formatted anchor tag
                    else
                        return null;
                })
                ->addColumn('class', function ($Booking) {
                    $class =  $Booking->appointment->class;
                     
                    return  $class->name ?? null; // Properly formatted anchor tag
                
                })
                ->addColumn('start_time', function ($Booking) {
                    $appointment =  $Booking->appointment;
                    if ($appointment !== null)
                        return   $appointment->start_time; // Properly formatted anchor tag
                    else
                        return null;
                })
                 ->addColumn('created_at',function($Booking){

                    $Booking->created_at = Carbon::parse($Booking->created_at)->format('Y-m-d H:i:s'); // Format it as per your needs
                    return $Booking->created_at;
                 })
                ->addColumn('seat', function ($Booking) {
                    $seat =  $Booking->seat;
                    if ($seat !== null)
                        return   $seat->seat_number; // Properly formatted anchor tag
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
                ->rawColumns(['username', 'appointment','class' ,'seat', 'status', 'action','start_time','created_at']) // Specify columns  
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

         
        $bookedSeats = Booking::where('appointment_id', $appointmentId)
            ->pluck('seat_id')
            ->toArray();   

         $seats = SeatPoint::where('class_id', $appointment->class_id)->get();
 
        return view('admin.appointment.seat.index', [
            'seatPoints' => $seats,
            'bookedSeats' => $bookedSeats,
            'appointmentId' => $appointmentId
        ]);
    }

 



    public function class_seat(Request $request)
    {

        // 'seat_number', 'seat_not_active', 'line', 'class_id', 'note's
        if ($request->ajax()) {
            $SeatPoint = SeatPoint::with('class:id,name,room') // Eager load the 'class' relationship
                ->select('id',  'seat_number', 'line', 'note', 'class_id');

            return DataTables::of($SeatPoint)
                ->addColumn('class', function ($seat) {
                    return   $seat->class->name ;
                })

                ->addColumn('room', function ($seat) {
                    return   $seat->class->room  ;
                })
                ->rawColumns(['id', 'class', "room", 'seat_number', 'line', 'note']) // Specify columns  
                ->make(true);
        }

        return view('admin.appointment.seat.class_seat');
    }

    public function searchSeatsByClass(Request $request)
    {
         
        $request->validate([
            'class_id' => 'required|integer|exists:appointments,id',
        ]);
 
        $appointmentId = $request->input('class_id');
        $appointment = Appointment::find($appointmentId);

 
        $bookedSeats = Booking::where('appointment_id', $appointmentId)
            ->pluck('seat_id')
            ->toArray();   

 
        $seats = SeatPoint::where('class_id', $appointment->class_id)->get();

       
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
            $coaches = Coache::with('user')->get();

            return view('admin.appointment.create', compact('classes', 'coaches'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    public function store(StoreAppointmentRequest $request)
    {
        try {
            $validated = $request->validated(); // Use validated data

            Appointment::create($validated);

            return redirect()->route('admin.appointments.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }


    public function edit(Appointment $appointment)
    {
        $classes = ClassModel::select('id', 'name')->get();
        $coaches = Coache::with('user')->get();
        return view('admin.appointment.edit', compact('appointment', 'classes', 'coaches'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        try {
            $validated = $request->validated(); // Use validated data
            $appointment->update($validated);

            return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }
}
