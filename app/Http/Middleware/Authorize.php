<?php

namespace App\Http\Middleware;

use Closure;
use App\Domain;
use Illuminate\Support\Facades\Auth;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domain = Domain::find($request->route('domain'));
        if($domain->user()->first()->id == Auth::guard('api')->id()) {
            return $next($request);
        }
        return response()->json(['error' => 'Unautorized Access'],403);
    }
}
