<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClassUser;
use App\Models\ClassModel;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardContrtoller extends Controller
{
    //


    public function index()
    {
        $bookingClassUser = BookingClassUser::select(
            DB::raw('SUM(class_completed) as total_class_completed'),
            DB::raw('SUM(quantity) as total_quantity')
        )->first();
    
        $users = User::where('is_coache', 0)->count();
        $coaches = User::where('is_coache', 1)->count();
    
        // Fetch transaction data
        $transactions =  Transaction::select(
            DB::raw('DATE(transaction_time) as date'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
    
        return view('admin.index3', [
            "data" => [100000, 2000, 3000, 2500, 2700, 2500, 3000],
            "bookingClassUser" => $bookingClassUser,
            "users" => $users,
            "coaches" => $coaches,
            "transactions" => $transactions,
        ]);
    }
    
}
