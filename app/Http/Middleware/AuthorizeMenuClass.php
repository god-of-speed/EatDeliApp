<?php

namespace App\Http\Middleware;

use Closure;
use App\MenuClass;
use Illuminate\Support\Facades\Auth;

class AuthorizeMenuClass
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
        $menuClass = MenuClass::find($request->route('menuClass'));
        if($menuClass->domain()->first()->user()->first()->id == Auth::guard('api')->id()) {
            return $next($request);
        }
        return response()->json(['error' => 'Unautorized Access'],403);
    }
}
