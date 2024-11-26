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
            
            $users = User::select('id', 'username', 'first_name', 'last_name', 'phone', 'gender', 'email', 'dob', 'photo',  'created_at')
            ->where('is_coache','=',false)
            ->where('is_active','=',true);

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
     
        return view('admin.users.index', compact('users'));
    }

    public function not_active(Request $request)
    {
         if ($request->ajax()) {
            
            $users = User::select('id', 'username', 'first_name', 'last_name', 'phone', 'gender', 'email', 'dob', 'photo',  'is_active', 'created_at')
            ->where('is_coache','=',false)
            ->where('is_active','=',false);

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
    
}
