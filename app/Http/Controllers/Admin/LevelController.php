<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function  index(Request $request){

        if ($request->ajax()) {

            $Plan = Level:: select('id', 'title_levels', 'required_classes'  );

            return DataTables::of($Plan)

                ->make(true);
        }

        return view('admin.levels.index');
        
    }
}
