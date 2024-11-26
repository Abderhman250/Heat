<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\PlanRequest;
use App\Http\Resources\HistoryPlansResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\PlanShowResource;
use App\Models\BookingClassUser;
use App\Models\ClassModel;
use App\Models\Plan;
use App\Models\SectionPlan;
use App\Models\Transaction;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 5);
        $plans = SectionPlan::with('plans')
            ->has('plans')
            ->paginate($perPage);
        // $plans = ClassModel::with('plans')
        // ->has('plans')
        // ->paginate($perPage);
        return ApiResponse::success(
            [
                "collect" => PlanResource::collection($plans),
                'pagination' => ApiResponse::paginationData($plans),

            ],
            'Successfully list coach resource.',
            314
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PlanRequest $request, $id)
    {
        $perPage = (int) $request->query('per_page', 5);
        $plans = ClassModel::with('plans')
            ->has('plans')
            ->paginate($perPage);

        return ApiResponse::success(
            [
                "collect" => PlanShowResource::collection($plans),
                'pagination' => ApiResponse::paginationData($plans),

            ],
            'Successfully list coach resource.',
            314
        );
    }


    /**
     * transaction the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function historyPlans(PlanRequest $request)
    {
        $perPage = (int) $request->query('per_page', 5);

        $plans = UserPlan::orderBy('created_at','desc')->paginate($perPage);

        return ApiResponse::success(
            [
                "collect" => HistoryPlansResource::collection($plans),

            ],
            'Successfully list coach resource.',
            314
        );
    }



    public function checkout(CheckoutRequest $request)
    {
        // Retrieve validated data from the request
        $validated = $request->validated();
        $user_id = auth()->user()->id;
        DB::beginTransaction();

        try {
            UserPlan::create([
                'plan_id' => $validated['plan_id'],
                'user_id' => $user_id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addYears(5),
            ]);

            // Create the transaction
            Transaction::create([
                'user_id' => $user_id,
                'plan_id' => $validated['plan_id'],
                'transaction_status' => $validated['transaction_status'],
                'payment_method' => $validated['payment_method'],
                'amount' => $validated['amount'],
                'is_successful' => $validated['is_successful'],
                'transaction_time' => Carbon::parse($validated['transaction_time']), // Ensure the transaction time is in correct format
            ]);

            if ($validated['is_successful'] === true) {

                $Plan =  Plan::find($validated['plan_id']);
                $BookingClassUser = BookingClassUser::updateOrCreate(
                    [
                        'user_id' => $user_id,    // Search by user_id and class_id
                        'class_id' => $Plan->class_id,
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $Plan->total_classes),  // Increment quantity if record exists, set it if not
                        'class_completed' => true,  // Mark as completed if relevant
                    ]
                );
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();

            Log::error('Failed to checkout: ' . $e->getMessage());

            return response()->json(['error' => 'Checkout failed, please try again later.'], 500);
        }
        return ApiResponse::success([], 'Transaction successfully created', 316);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
