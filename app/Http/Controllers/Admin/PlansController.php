<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Plan;
use App\Models\SectionPlan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlansController extends Controller
{
    public function  index(Request $request)
    {

        if ($request->ajax()) {

            $Plan = Plan::with(['class:id,name'])->select('id', 'plan_name', 'class_id', 'total_classes', 'description', 'photo', 'price');

            return DataTables::of($Plan)
                ->addColumn('class', function ($Plan) {
                    return    $Plan->class->name;
                })
                ->addColumn('photo', function ($Plan) {
                    return    '
                              <div class="user-panel mt-1 pb-1 mb-1 d-flex" >
                                <div class="image" >
                                    <img src="'.$Plan->photo.'" class="img-circle elevation-2" alt="User Image" style="height: 4.1rem;width: 4.1rem;">
                                 </div>
                              </div> ';
                })



                ->rawColumns(['class','photo']) // Specify columns  
                ->make(true);
        }

        return view('admin.plans.index');
    }


    public function  section_plans(Request $request){

        if ($request->ajax()) {

            $Plan = SectionPlan:: select('id', 'section_name', 'description');

            return DataTables::of($Plan)

 
                ->make(true);
        }

        return view('admin.section_plans.index');
        
    }


    public function create()
    {
        $classes      = ClassModel::select('id' , 'name')->get();
        $sectionPlans = SectionPlan::select('id' , 'section_name')->get();
        return view('admin.plans.create' ,compact('classes' , 'sectionPlans') );
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'plan_name'        => 'required|string|max:255',
                'type'             => 'required|string|max:50',
                'class_id'         => 'required|exists:classes,id',
                'total_classes'    => 'required|integer|min:1',
                'description'      => 'nullable|string',
                'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price'            => 'required|numeric|min:0',
                'section_plan_id'  => 'required|exists:section_plans,id',
            ]);

            if ($request->file('photo')) 
                $validated['photo'] = $request->getSchemeAndHttpHost().'/storage/'.$request->file('photo')->store('plans', 'public');

        
            Plan::create($validated);

            return redirect()->route('admin.plans.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }
}
