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
    //     $PlanID16 = Plan::with(['class:id,name', 'sectionPlanOne:id,section_name'])
    //     ->select('id', 'plan_name', 'type', 'class_id', 'total_classes', 'description', 'photo', 'price','section_plan_id')
    //     ->where('id', 16)
    //     ->first();
 
    //     $PlanID1 = Plan::with(['class:id,name', 'sectionPlanOne:id,section_name'])
    //         ->select('id', 'plan_name', 'type', 'class_id', 'total_classes', 'description', 'photo', 'price','section_plan_id')
    //         ->where('id', 1)
    //         ->first();
  
    
    // dd(["PlanID16" => $PlanID16->sectionPlanOne->section_name ,"PlanID1" => $PlanID1->sectionPlanOne->section_name] );

        if ($request->ajax()) {

            $Plan = Plan::with(['class:id,name', 'sectionPlanOne:id,section_name'])->select('id', 'plan_name', 'type','class_id', 'total_classes', 'description', 'photo', 'price','active','section_plan_id');

            return DataTables::of($Plan)
                ->addColumn('class', function ($Plan) {
                    return    $Plan->class->name;
                })
                // ->addColumn('photo', function ($Plan) {
                //     return    '
                //               <div class="user-panel mt-1 pb-1 mb-1 d-flex" >
                //                 <div class="image" >
                //                     <img src="'.$Plan->photo.'" class="img-circle elevation-2" alt="User Image" style="height: 4.1rem;width: 4.1rem;">
                //                  </div>
                //               </div> ';
                // })
                
                ->addColumn('sectionPlan', function ($plan) {
                   
                    return $plan->sectionPlan->section_name ?? '-' ;
                })
                ->addColumn('active', function ($plan) {
                     if ($plan->active == 0) {
                        // User is active -> Show deactivate button
                        return '<button class="btn btn-success btn-sm deactivate-plan" data-id="' . $plan->id . '">Activate </button>';
                    } else {
                        // User is not active -> Show activate button
                        return '<button class="btn btn-danger btn-sm activate-plan" data-id="' . $plan->id . '">Deactivate</button>';
                    }
                })
                ->rawColumns(['class','photo','sectionPlan','active']) // Specify columns  
                ->make(true);
        }

        return view('admin.plans.index');
    }


    public function  section_plans(Request $request){

        if ($request->ajax()) {

            $Plan = SectionPlan:: select('id', 'section_name', 'description');

            return DataTables::of($Plan)

                ->addColumn('edit', function ($Plan) {
                    return '<a href="' . route('admin.section_plans.edit', $Plan->id) . '" 
                                class="btn btn-sm btn-block btn-outline-info btn-xs">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>';
                })
                ->rawColumns(['edit'])
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
                // 'type'             => 'required|string|max:50',
                'class_id'         => 'required|exists:classes,id',
                'total_classes'    => 'required|integer|min:1',
                'description'      => 'nullable|string',
                // 'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price'            => 'required|numeric|min:0',
                'section_plan_id'  => 'required|exists:section_plans,id',
            ]);

            // if ($request->file('photo')) 
            //     $validated['photo'] = $request->getSchemeAndHttpHost().'/storage/'.$request->file('photo')->store('plans', 'public');

            $validated['photo']="https://via.placeholder.com/640x480.png/003388?text=sports+distinctio";
            $validated['type']="";

 
            Plan::create($validated);

            return redirect()->route('admin.plans.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }


    
    public function activatePlan(Request $request)
    {
        $plan = Plan::find($request->id);
         
        $plan->active = true;
        $plan->save();

        return response()->json(['message' => 'Plan activated successfully.']);
    }

    public function deactivatePlan(Request $request)
    {
        $plan = Plan::find($request->id);
        $plan->active = false;
        $plan->save();

        return response()->json(['message' => 'Plan deactivated successfully.']);
    }
}
