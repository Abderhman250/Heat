<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckCompletingInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        $route = Request()->route()->getName();
        
        $route_expext =  ['user.info','user.edit.profile'];


        if (in_array($route, $route_expext))
            return $next($request);

        $user = Auth::user();
 
        if ($user && !$user->completing_info) 
            return ApiResponse::error("Please complete your information before proceeding.", Response::HTTP_UNAUTHORIZED);
      

        return $next($request);
    }
}
