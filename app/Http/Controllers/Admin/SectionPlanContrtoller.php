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
