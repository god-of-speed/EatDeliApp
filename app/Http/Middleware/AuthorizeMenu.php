<?php

namespace App\Http\Middleware;

use Closure;
use App\Menu;
use Illuminate\Support\Facades\Auth;

class AuthorizeMenu
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
        $menu = Menu::find($request->route('menu'));
        if($menu->domain()->first()->user()->first()->id == Auth::guard('api')->id()) {
            return $next($request);
        }
        return response()->json(['error' => 'Unautorized Access'],403);
    }
}
