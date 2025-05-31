<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationMobile;
use App\Models\User;
use App\Models\DeviceToken;

use App\Http\Requests\Admin\NotificationRequest;
use App\Services\FirebaseService;

use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class NotificationController extends Controller
{

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

                $datas = NotificationMobile::with(['getUser'])->get();

                return DataTables::of($datas)
                ->addColumn('User', function ($plan) {
                   
                    return $plan->getUser->email ?? '-' ;
                })

                ->make(true);
        }

        $datas = NotificationMobile::with(['getUser'])->get();

        return view('admin.notifications.index', compact('datas'));
    }

    public function create()
    {
        $datas = User::select(
            'users.id',
            'users.email'
        )
        ->join('role_user', 'users.id', '=', 'role_user.user_id') 
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('roles.name', 'user')
        ->where('is_coache', '=', false)
        ->where('enable_notification', true)
        ->get();

        $datas = DeviceToken::whereHas('user' , function ($query) {
            $query->where('enable_notification', true);
        })->get();


        return view('admin.notifications.create', compact('datas'));
    }


    private function insertNotificationUser($data)
    {
        foreach ($data['users'] as $user_id)
        {
            NotificationMobile::create([
                'title'          =>  $data['title'],
                'body'           =>  $data['body'],
                'user_id'        =>  $user_id,
                'created_at'     =>  Carbon::now(),
                'updated_at'     =>  Carbon::now(),
            ]);
        }
    }

    public function push(NotificationRequest $request)
    {
        $validated = $request->validated();

        $this->insertNotificationUser($request);
        $response = $this->firebaseService->sendNotificationToMultipleUsers($request['device_tokens'], $request['title'], $request['body']);

        return redirect()->route('admin.notifications.index');
    }

}
