<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLevelRequest;
use App\Http\Requests\Admin\UpdateLevelRequest;
use App\Models\Level;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function  index(Request $request)
    {

        if ($request->ajax()) {

            $Level = Level::select('id', 'title_levels', 'required_classes');
            return DataTables::of($Level)
            ->addColumn('edit', function ($Level) {
                return '<a href="' . route('admin.level.edit', $Level->id) . '" 
                            class="btn btn-sm btn-block btn-outline-info btn-xs">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>';
            })
            ->rawColumns(['edit'])
            ->make(true);
        
        
        
        }

        return view('admin.levels.index');
    }


    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(StoreLevelRequest $request)
    {
        try {
            // Use the validated data from the request
            Level::create($request->validated());

            return redirect()->route('admin.level.index')->with('success', 'Level created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $level = Level::findOrFail($id); // Fetch the level by ID
        return view('admin.levels.edit', compact('level'));
    }


    public function update(UpdateLevelRequest $request, $id)
    {
        $level = Level::findOrFail($id);

        $level->update([
            'title_levels' => $request->input('title_levels'),
            'required_classes' => $request->input('required_classes'),
        ]);

        return redirect()->route('admin.level.index')->with('success', 'Level updated successfully!');
    }
}
