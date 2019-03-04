<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //get initial variables
        return view('home');
    }


    public function homeIndex(Request $request) {
        // $data = $request->all();
        // //get search key
        // $search = $data['search'];
        //get products
        $products = Product::where('isSet',true)->latest()->paginate(20);
        return response()->json(compact('products'),200);
    }

    public function checking() {
        dd(Auth::guard('api')->user()->api_token);
    }
}
