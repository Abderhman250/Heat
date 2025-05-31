<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserContrtoller extends Controller
{
    //



    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::select(
                'users.id',
                'users.username',
                'users.first_name',
                'users.last_name',
                'users.phone',
                'users.gender',
                'users.is_active',
                'users.email',
                'users.dob',
                'users.photo',
                'users.created_at'
            )
            ->join('role_user', 'users.id', '=', 'role_user.user_id') // Assuming role_user is the pivot table
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'user')
            ->where('is_coache', '=', false);

            return DataTables::of($users)
                // Gender Column
                ->addColumn('gender', function ($user) {
                    return $user->gender == 0 ? 'Male' : 'Female';
                })

                // Active/Deactivate Button Column
                ->addColumn('active', function ($user) {
                    if ($user->is_active) {
                        // User is active -> Show deactivate button
                        return '<button class="btn btn-danger btn-sm deactivate-user" data-id="' . $user->id . '">Deactivate</button>';
                    } else {
                        // User is not active -> Show activate button
                        return '<button class="btn btn-success btn-sm activate-user" data-id="' . $user->id . '">Activate</button>';
                    }
                })

                ->addColumn('photo', function ($user) {
                    return    '
                              <div class="user-panel mt-1 pb-1 mb-1 d-flex" >
                                <div class="image" >
                                    <img src="'.$user->photo.'" class="img-circle elevation-2" alt="User Image" style="height: 4.1rem;width: 4.1rem;">
                                 </div>
                              </div> ';
                })

                ->rawColumns(['active','photo']) // Render HTML in active column
                ->make(true);
        }

        $users = User::paginate(10); // This line is optional; you might not need it if you are using DataTables fully.

        return view('admin.users.index', compact('users'));
    }

    public function not_active(Request $request)
    {
        if ($request->ajax()) {

            $users = User::select('id', 'username', 'first_name', 'last_name', 'phone', 'gender', 'email', 'dob', 'photo',  'is_active', 'created_at')
                ->where('is_coache', '=', false)
                ->where('is_active', '=', false);

            return DataTables::of($users)
                ->addColumn('gender', function ($user) {
                    return $user->gender == 0 ? 'Male' : 'Female';
                })

                ->addColumn('action', function ($user) {
                    return '
                     
                            <form action="' . 1 . '" method="POST" style="display:inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-warning btn-md"> Action</button>
                            </form>';
                })
                ->make(true);
        }

        $users = User::paginate(10); // This line is optional; you might not need it if you are using DataTables fully.

        return view('admin.users.not_active', compact('users'));
    }


    public function activateUser(Request $request)
    {
        $user = User::find($request->id);
        $user->is_active = true;
        $user->save();

        return response()->json(['message' => 'User activated successfully.']);
    }

    public function deactivateUser(Request $request)
    {
        $user = User::find($request->id);
        $user->is_active = false;
        $user->save();

        return response()->json(['message' => 'User deactivated successfully.']);
    }
}
