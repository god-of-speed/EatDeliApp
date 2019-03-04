<?php

namespace App\Http\Middleware;

use Closure;
use App\Product;
use Illuminate\Support\Facades\Auth;

class AuthorizeProduct
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
        $product = Product::find($request->route('product'));
        if($product->domain()->first()->user()->first()->id == Auth::guard('api')->id()) {
            return $next($request);
        }
        return response()->json(['error' => 'Unautorized Access'],403);
    }
}
