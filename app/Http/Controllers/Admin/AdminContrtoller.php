<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
     
        return view('admin.admins.index', compact('users'));
    }
}
