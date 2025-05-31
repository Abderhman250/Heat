<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\UpdateAdminManagerRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
class AdminContrtoller extends Controller
{
    public function index(Request $request)
    {
         if ($request->ajax()) {
            
            $users = User::select('users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.phone', 'users.gender', 'users.email', 'users.dob', 'users.photo', 'users.is_active', 'users.created_at')
            ->join('role_user', 'users.id', '=', 'role_user.user_id') // Assuming role_user is the pivot table
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'admin');

            return DataTables::of($users)
                ->addColumn('gender', function ($user) {
                    return $user->gender == 1 ? 'Male' : 'Female';
                })
          
                ->addColumn('action', function ($user) {
                  
                    return '<a href="' . route('admin.admins.edit', $user->id) . '" 
                                    class="btn btn-sm btn-block btn-outline-info btn-xs">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>';
                })
                ->rawColumns(['gender', 'action']) 

                ->make(true);
        }

        $users = User::paginate(10);  
     
        return view('admin.admins.index', compact('users'));
    }

    public function edit($id)
    {
        $admin = User::find($id);

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminManagerRequest $request, $id)
    {
        try {
 
            $user = User::findOrFail($id);
            $photo =  $user->photo;
            // Update photo if uploaded
            // if ($request->file('photo')) {
            //     $photo = $request->getSchemeAndHttpHost() . '/storage/' . $request->file('photo')->store('coach', 'public');
            //     $coach->profile_photo = $photo;
            //     $user->photo = $photo;
            // }

            // Update User
            $user->update([
                'first_name'    => $request->input('first_name'),
                'last_name'     => $request->input('last_name'),
                // 'phone'         => $request->input('phone'),
                'photo'         => $photo,
                'country_code'  => $request->input('country_code'),
                'gender'        => $request->input('gender'),
                'email'         => $request->input('email'),
                'dob'           => $request->input('dob', $user->dob),
                'username' => $request->username,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'updated_at'    => Carbon::now(),
            ]);

    

            return redirect()->route('admin.admins.index')->with('success', 'Admin Manager updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }


}
