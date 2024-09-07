<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class StatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth()->check()) {
            if (!Auth()->user()->status) {
                if ($request->expectsJson()) {
                    // $value = $request->bearerToken();
                    // $id = (new Parser())->parse($value)->getHeader('jti');
                    // $token= $request->users()->tokens->find($id);
                    // $token->revoke();
                } else {
                    Auth::logout();
                }
                return $request->expectsJson()
                    ? response()->json(['status' => false, 'data' => __('return.disabled')], 403)
                    : redirect('/')->with('error', __('return.disabled'));
            }
        }
        return $next($request);
    }
}
