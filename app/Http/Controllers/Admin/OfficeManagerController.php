<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests\Admin\UpdateOfficeManagerRequest;
use Yajra\DataTables\Facades\DataTables;

class OfficeManagerController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select('users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.phone', 'users.gender', 'users.email', 'users.dob', 'users.photo', 'users.is_active', 'users.created_at')
            ->join('role_user', 'users.id', '=', 'role_user.user_id') // Assuming role_user is the pivot table
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'office_manager');


            return DataTables::of($users)
                ->addColumn('gender', function ($user) {
                    return $user->gender == 1 ? 'Male' : 'Female';
                })
                ->addColumn('action', function ($user) {
                  
                    return '<a href="' . route('admin.office_manager.edit', $user->id) . '" 
                                    class="btn btn-sm btn-block btn-outline-info btn-xs">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>';
                })
                ->addColumn('photo', function ($user) {
                    return    '
                                  <div class="user-panel mt-1 pb-1 mb-1 d-flex" >
                                    <div class="image" >
                                        <img src="' . $user->photo . '" class="img-circle elevation-2" alt="User Image" style="height: 4.1rem;width: 4.1rem;">
                                     </div>
                                  </div> ';
                })

                ->rawColumns(['photo', 'action']) // Render HTML in active column

                ->make(true);
        }

        $users = User::paginate(10); // This line is optional; you might not need it if you are using DataTables fully.

        return view('admin.office_manager.index', compact('users'));
    }


    public function create()
    {
        return view('admin.office_manager.create');
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'    => 'required|string|max:255',
                'last_name'     => 'required|string|max:255',
                'phone'         => 'nullable|string|max:20',
                'country_code'  => 'nullable|string|max:5',
                'gender'        => 'nullable|boolean',
                'email'         => 'nullable|email|unique:users,email',
                'dob'           => 'nullable|date',
                'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password'      => 'nullable|string|min:8',
                'username'      => 'required|string|max:255',
 
            ]);

            if ($request->file('photo'))
                $photo = $request->getSchemeAndHttpHost() . '/storage/' . $request->file('photo')->store('office_manager', 'public');


            $user_id = User::insertGetId([
                'first_name'       => $validated['first_name'],
                'last_name'        => $validated['last_name'],
                'username'         => $validated['username'],
                'password'         => bcrypt($validated['password']),
                'phone'            => $validated['phone'],
                'country_code'     => $validated['country_code'],
                'gender'           => $validated['gender'],
                'email'            => $validated['email'],
                'dob'              => $validated['dob'],
                'level_id'         => null,
                'photo'            => $photo ?? NULL,
                'is_coache'        => FALSE,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);

            $user = User::find($user_id);

            // Assign the role to the user
            $user->syncRoles(['office_manager']);

            return redirect()->route('admin.office_manager.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        // $coach = Coache::with('user')->findOrFail($id);
        $office_manager = User::find($id);

        return view('admin.office_manager.edit', compact('office_manager'));
    }

    public function update(UpdateOfficeManagerRequest $request, $id)
    {
        try {
 
            $user = User::findOrFail($id);
            $photo =  $user->photo;
            // Update photo if uploaded
            if ($request->file('photo')) {
                $photo = $request->getSchemeAndHttpHost() . '/storage/' . $request->file('photo')->store('coach', 'public');
                $coach->profile_photo = $photo;
                $user->photo = $photo;
            }

            // Update User
            $user->update([
                'first_name'    => $request->input('first_name'),
                'last_name'     => $request->input('last_name'),
                'phone'         => $request->input('phone'),
                'photo'         => $photo,
                'country_code'  => $request->input('country_code'),
                'gender'        => $request->input('gender'),
                'email'         => $request->input('email'),
                'dob'           => $request->input('dob', $user->dob),
                'updated_at'    => Carbon::now(),
            ]);

    

            return redirect()->route('admin.office_manager.index')->with('success', 'Office Manager updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }
}
