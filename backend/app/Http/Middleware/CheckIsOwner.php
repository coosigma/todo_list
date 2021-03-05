<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckIsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestedUserId = $request->route()->parameter('id');
        if(Auth::user()->id == $requestedUserId) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
