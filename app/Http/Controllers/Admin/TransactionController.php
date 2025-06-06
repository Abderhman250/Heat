<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TransactionController extends Controller
{
    //
public function  index(Request $request){
  
    if ($request->ajax()) {

        $Transaction =Transaction::with(['user:id,username', 'plan:id,plan_name'])->select('id', 'user_id', 'plan_id', 'transaction_status', 'payment_method', 'amount', 'is_successful', 'transaction_time');
        
        if ($request->has('plan') && $request->plan != '') {
            $Transaction->whereHas('plan', function ($query) use ($request) {
                $query->where('plan_name', 'like', '%' . $request->plan . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $Transaction->where('is_successful', '=',$request->status);
   
        }
        // Apply date filter if provided
        if ($request->has('date') && $request->date != '') {
            
            $Transaction =  $Transaction->whereDate('transaction_time', '=', Carbon::parse($request->date)->toDateString());
        }
        return DataTables::of($Transaction)
            ->addColumn('username', function ($Transaction) {
                return    $Transaction->user->username  ;
            })
            ->addColumn('plan', function ($Transaction) {
                $plan =  $Transaction->plan;
                return  ($plan !== null) ? $plan->plan_name : null; // Properly formatted anchor tag
        
            })
 
            ->rawColumns(['username', 'plan']) // Specify columns  
            ->make(true);
    }

    return view('admin.transaction.index');
}
    
}
