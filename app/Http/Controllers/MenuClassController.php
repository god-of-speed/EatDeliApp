<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Product;
use App\MenuClass;
use Illuminate\Http\Request;
use App\Service\GeneratorService;
use Illuminate\Support\Facades\Auth;

class MenuClassController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->only('store','update','destroy','edit');
        $this->middleware('authorizeMenu')->only('store');
        $this->middleware('authorizeMenuClass')->only('update','destroy','edit');
    }

    


    public function show($menuClass) {
        //get domain info
        $menuClass = MenuClass::find($menuClass);
        if($menuClass) {
            //check if user is the admin of the domain
            $isAdmin = Auth::guard('api')->id() == $menuClass->domain()->first()->user()->first()->id;

            return response()->json([
                "data" => [
                    "menuClass" => $menuClass->toArray(),
                    "isAdmin" => $isAdmin
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function store(Request $request,GeneratorService $generatorService,$menu) {
        //get menu
        $menu = Menu::find($menu);
        if($menu) {
            //get all input
            $data = $request->all();
            //validate inputs
            $validator = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            //create new domain
            $menuClass = MenuClass::create([
                'title' => ucwords($data['title']),
                'description' => $data['description'],
                'domain_id' => $menu->domain()->first()->id,
                'menu_id' => $menu->id
            ]);

            if($menuClass) {
                return response()->json([
                    "data" => [
                        "menuClass" => $menuClass->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function index($menuClass) {
        //get menu class
        $menuClass = MenuClass::find($menuClass);
        if($menuClass) {
            //get set menu
            $products = Product::where([
                ['menuClass_id',$menuClass->id],
                ['isSet',true]
                ])->get();
            //check if user is an admin
            $isAdmin = Auth::guard('api')->id() == $menuClass->domain()->first()->user()->first()->id;

            return response()->json([
                "data" => [
                    "products" => $products->toArray(),
                    'isAdmin' => $isAdmin,
                    'menuClass' => $menuClass
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }





    public function edit($menuClass) {
        //get menuClass
        $menuClass = MenuClass::find($menuClass);
        if($menuClass) {
            return response()->json([
                "data" => [
                    "menuClass" => $menuClass->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }





    public function update(Request $request,$menuClass) {
        //get domain
        $menuClass = MenuClass::find($menuClass);
        if($menuClass) {
            //get all input
            $data = $request->all();
            //validate inputs
            $validator = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);
        
            //create new domain
            $menuClassUpdate = $menuClass->update([
                'title' => ucwords($data['title']),
                'description' => $data['description']
            ]);

            if($menuClass) {
                return response()->json([
                    "data" => [
                        "menuClass" => $menuClass->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500); 
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function destroy($menuClass) {
        //get domain
        $menuClass = MenuClass::find($menu);
        if($menuClass) {
            $menuClass->delete();
            return response()->json(['data' => true],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }
}
