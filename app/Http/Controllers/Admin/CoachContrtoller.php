<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Coache;
use Carbon\Carbon;
use App\Models\Level;
class CoachContrtoller extends Controller
{
    public function index(Request $request)
    {
         if ($request->ajax()) {
            
            $users = User::select('id', 'username', 'first_name', 'last_name', 'phone', 'gender', 'email', 'dob', 'photo',  'is_active', 'created_at')
            ->where('is_coache','=',true);

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
     
        return view('admin.coach.index', compact('users'));
    }


    public function create()
    {
        $levels = Level::select('id', 'title_levels')->get();
        return view('admin.coach.create' , compact('levels'));
    }


    public function store(Request $request)
    {
        try{
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
                'specialty'     => 'required|string|max:255',
                'bio'           => 'nullable|string',
                'level_id'      => 'nullable|exists:levels,id',
            ]);

            if ($request->file('photo'))
                $photo = $request->getSchemeAndHttpHost().'/storage/'.$request->file('photo')->store('coach', 'public');


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
                'level_id'         => $validated['level_id'],
                'photo'            => $photo ?? NULL,
                'is_coache'        => True,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);


            Coache::create([
                'username'        => $validated['username'],
                'specialty'       => $validated['specialty'],
                'bio'             => $validated['bio'],
                'user_id'         => $user_id,
                'profile_photo'   => $photo ?? NULL,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            return redirect()->route('admin.coach.index');
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }

}
