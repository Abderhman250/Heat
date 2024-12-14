<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ClassType;
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

                ->addColumn('action', function ($class) {
                    return '
                                <form action="' . 1 . '" method="POST" style="display:inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-warning btn-md"> Action</button>
                            </form>';
                })
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

    public function store(Request $request)
    {
        try{
                $validated = $request->validate([
                    'name'                    => 'required|string|max:255',
                    'room'                    => 'nullable|string|max:255',
                    'capacity'                => 'required|integer|min:1',
                    'seat_selection_required' => 'required|boolean',
                    'type_name'               => 'required|string|in:sint,odio,occaecati,consequuntur',
                    'booking_process'         => 'required|string|in:In-Person,Online',
                    'description'             => 'nullable|string',
                    'photo'                   => 'nullable|image|max:2048',
                ]);

                if($request->file('photo'))
                    $photo = $request->getSchemeAndHttpHost().'/storage/'.$request->file('photo')->store('classes', 'public');

                $class_type_id = ClassType::insertGetId([
                    'type_name'       => $validated['type_name'],
                    'booking_process' => $validated['booking_process'],
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);

                ClassModel::create([
                    'name'                         => $validated['name'],
                    'room'                         => $validated['room'],
                    'capacity'                     => $validated['capacity'],
                    'seat_selection_required'      => $validated['seat_selection_required'],
                    'description'                  => $validated['description'],
                    'class_type_id'                => $class_type_id,
                    'photo'                        => $photo ?? NULL,
                ]);
            return redirect()->route('admin.classes.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }

}
