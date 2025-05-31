<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCoachRequest;
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
            $users = User::select(
                'id',
                'username',
                'first_name',
                'last_name',
                'phone',
                'gender',
                'email',
                'dob',
                'photo',
                'is_active',
                'created_at'
            )
                ->where('is_coache', '=', true)
                ->whereHas('coach', function ($q) {
                    $q->whereNotNull('user_id'); // Adjust the condition based on your needs
                });

            return DataTables::of($users)
                ->addColumn('gender', function ($user) {
                    return $user->gender == 1 ? 'Male' : 'Female';
                })
                ->addColumn('action', function ($user) {
                    $coach = Coache::where('user_id', $user->id)->first();


                    return '<a href="' . route('admin.coach.edit', $coach->id) . '" 
                                class="btn btn-sm btn-block btn-outline-info btn-xs">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>';
                })
                ->addColumn('photo', function ($user) {
                    return    '
                              <div class="user-panel mt-1 pb-1 mb-1 d-flex" >
                                <div class="image" >
                                    <img src="'.$user->photo.'" class="img-circle elevation-2" alt="User Image" style="height: 4.1rem;width: 4.1rem;">
                                 </div>
                              </div> ';
                })
                
                ->rawColumns(['photo','action']) // Render HTML in active column

                ->make(true);
        }

        $users = User::paginate(10); // This line is optional; you might not need it if you are using DataTables fully.

        return view('admin.coach.index', compact('users'));
    }


    public function create()
    {
        return view('admin.coach.create');
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
                'specialty'     => 'required|string|max:255',
                'bio'           => 'nullable|string',
            ]);

            if ($request->file('photo'))
                $photo = $request->getSchemeAndHttpHost() . '/storage/' . $request->file('photo')->store('coach', 'public');


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
            
            $user = User::find($user_id);

            $user->syncRoles(['user']);

            return redirect()->route('admin.coach.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $coach = Coache::with('user')->findOrFail($id);
        return view('admin.coach.edit', compact('coach'));
    }

    public function update(UpdateCoachRequest $request, $id)
    {
        try {
            $coach = Coache::findOrFail($id);
            $user = User::findOrFail($coach->user_id);
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
                'dob'           => $request->input('dob',$user->dob),
                'updated_at'    => Carbon::now(),
            ]);

            // Update Coach
            $coach->update([
                'specialty'     => $request->input('specialty'),
                'bio'           => $request->input('bio'),
                'updated_at'    => Carbon::now(),
            ]);

            return redirect()->route('admin.coach.index')->with('success', 'Coach updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()])->withInput();
        }
    }
}
