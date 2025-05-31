<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionPlan;

class SectionPlanContrtoller extends Controller
{
    public function index()
    {
        return view('admin.index3',["data"=>['sss']]);
    }

    public function create()
    {
        return view('admin.section_plans.create' );
    }

    public function edit($id)
    { 
        $section_plan = SectionPlan::findOrFail($id); // Fetch the level by ID
        return view('admin.section_plans.edit', compact('section_plan'));
    }

    public function update(Request $request,$id)
    {
        try{
            $validated = $request->validate([
                'section_name' => 'required|string|max:255',
                'description'  => 'nullable|string',
            ]);
             
            $SectionPlan = SectionPlan::findOrFail($id);
            $SectionPlan->update([
                'section_name' =>$validated['section_name'], 
                'description' =>$validated['description']
            ]);

            return redirect()->route('admin.section_plans.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'section_name' => 'required|string|max:255',
                'description'  => 'nullable|string',
            ]);
            SectionPlan::create($validated);

            return redirect()->route('admin.section_plans.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }
}
