<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateClassRequest;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ClassType;
use App\Models\SeatPoint;
use Carbon\Carbon;
class ClassContrtoller extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $classes = ClassModel::select('id', 'name', 'description', 'photo', 'room', 'seat_selection_required', 'capacity', 'created_at', 'class_type_id');
 
            return DataTables::of($classes)
            ->addColumn('photo', function ($class) {
                return  $class->photo ? '<img class="media-object rounded-circle" width="50" height="50"
                src="' . ($class->photo) . '"
                alt="image">'  :null;
            })   
            ->addColumn('class_type', function ($class) {
                return  $class->classType->type_name;
            })             
            
            ->addColumn('edit', function ($Level) {
                return '<a href="' . route('admin.classes.edit', $Level->id) . '" 
                            class="btn btn-sm btn-block btn-outline-info btn-xs">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>';
            })
            ->addColumn('photo', function ($user) {
                return    '
                          <div class="user-panel mt-1 pb-1 mb-1 d-flex" >
                            <div class="image" >
                                <img src="'.$user->photo.'" class="img-circle elevation-2" alt="User Image" style="height: 4.1rem;width: 4.1rem;">
                             </div>
                          </div> ';
            })
            ->rawColumns(['edit','class_type','photo'])

            ->make(true);
        }

        return view('admin.classes.index');




        // ->addColumn('action', function ($class) {
        //     return '
        //             <a href="' . route('admin.classes.edit', $class->id) . '" class="btn btn-warning btn-sm">Edit</a>
        //             <form action="' . route('admin.classes.destroy', $class->id) . '" method="POST" style="display:inline;">
        //                 ' . csrf_field() . '
        //                 ' . method_field('DELETE') . '
        //                 <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        //             </form>';
        // })
    }




    public function create()
    {
        return view('admin.classes.create');
    }

    public function     store(Request $request)
    {
        try{
                $validated = $request->validate([
                    'name'                    => 'required|string|max:255',
                    'room'                    => 'nullable|string|max:255',
                    // 'capacity'                => 'required|integer|min:1',
                    'seat_selection_required' => 'required|boolean',
                    'type_name'               => 'required|string|in:Spinning room (bikes room),Group strength / endurance class,Yoga studio',
                    // 'booking_process'         => 'required|string|in:In-Person,Online',
                    'description'             => 'nullable|string',
                    'photo'                   => 'nullable|image|max:2048',
                    
                ]);
 
                if($request->file('photo'))
                    $photo = $request->getSchemeAndHttpHost().'/storage/'.$request->file('photo')->store('classes', 'public');

                $class_type_id = ClassType::insertGetId([
                    'type_name'       => $validated['type_name'],
                    'booking_process' => 'In-Person',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);

              $ClassModel=   ClassModel::create([
                    'name'                         => $validated['name'],
                    'room'                         => $validated['room'],
                    'capacity'                     => 200,
                    'seat_selection_required'      => ($request->seat_selection_required == "1")?true:false,
                    'description'                  => $validated['description'],
                    'class_type_id'                => $class_type_id,
                    'photo'                        => $photo ?? NULL,
                ]);


                if($request->seat_selection_required == "1"){
                $seat = ["F-3", "F-4","F-5","F-6","T-6","T-5","T-4","T-3","T-2","T-1","F-1","F-2"];
                    foreach ($seat as $key => $value) {
                        SeatPoint::create([
                           'seat_number' =>'E.' . random_int(100,999) ,
                           'line'=>$value,
                           'seat_not_active' =>1, 
                           'note' => "The seat already exists.",
                           'class_id'=>$ClassModel->id
                        ]);
        
                    }
                }
            return redirect()->route('admin.classes.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $class = ClassModel::findOrFail($id); // Fetch the level by ID
        $class_type = ClassType::all();
        return view('admin.classes.edit', compact('class','class_type'));
    }


    public function update(UpdateClassRequest $request, $id)
    {
        try {
            // Find the class to update
            $class = ClassModel::findOrFail($id);

            // Validate and get the data from the request
            $validated = $request->validated();

            // Handle photo upload if it exists
            if ($request->hasFile('photo')) {
                $photo = $request->getSchemeAndHttpHost().'/storage/'.$request->file('photo')->store('classes', 'public');
            } else {
                $photo = $class->photo; // Retain the existing photo if no new photo is uploaded
            }

            // Update the class record
            $class->update([
                'name'                    => $validated['name'],
                'room'                    => $validated['room'],
                'capacity'                => 200,
                'description'             => $validated['description'],
                'photo'                   => $photo,
                'class_type_id'           => $validated['class_type_id'],
                'updated_at'              => Carbon::now(),
            ]);

            return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

}
