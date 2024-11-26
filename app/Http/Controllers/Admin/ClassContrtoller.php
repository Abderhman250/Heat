<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
}
