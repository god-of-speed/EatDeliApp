<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Domain;
use App\Product;
use App\MenuClass;
use Illuminate\Http\Request;
use App\Service\GeneratorService;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->only('store','update','destroy','edit');
        $this->middleware('authorize')->only('store');
        $this->middleware('authorizeMenu')->only('update','destroy','edit');
    }
    


    public function show($menu) {
        //get domain info
        $menu = Menu::find($menu);
        if($menu) {
            //check if user is the admin of the domain
            $isAdmin = Auth::guard('api')->id() == $menu->domain()->first()->user()->first()->id;

            return response()->json([
                "data" => [
                    'menu' => $menu->toArray(),
                    "isAdmin" => $isAdmin
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function store(Request $request,GeneratorService $generatorService,$domain) {
        $domain = Domain::find($domain);
        if($domain) {
            //get all input
            $data = $request->all();
            //validate inputs
            $validator = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            //create new menu
            $menu = Menu::create([
                'title' => ucwords($data['title']),
                'description' => $data['description'],
                'domain_id' => $domain->id
            ]);

            if($menu) {
                return response()->json([
                    'data' => [
                        'menu' => $menu->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500); 
        }
        return response()->json(['error' => 'Resource not found'],404);   
    }



    public function index($menu) {
        //get menu
        $menu = Menu::find($menu);
        if($menu) {
            //get all products under this menu
            $products = Product::where([
                ['menu_id',$menu->id],
                ['isSet',true]
            ])->get();

            //check if user is an admin
            $isAdmin = Auth::guard('api')->id() == $menu->domain()->first()->user()->first()->id;
            return response()->json([
                'data' => [
                    'products' => $products->toArray(),
                    'isAdmin' => $isAdmin,
                    'menu' => $menu
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404); 
    }





    public function edit($menu) {
        //get menu
        $menu = Menu::find($mind);
        if($menu) {
            return response()->json([
                "data" => [
                    "menu" => $menu->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404); 
    }




    public function update(Request $request,$menu) {
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

            //update menu
            $menuUpdate = $menu->update([
                'title' => ucwords($data['title']),
                'description' => $data['description'],
            ]);

            if($menu) {
                return response()->json([
                    "data" => [
                        "menu" => $menu->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function destroy($menu) {
        //get menu
        $menu = Menu::find($menu);
        if($menu) {
            $menu->delete();
            return response()->json(['data' => true],200);;
        }
        return response()->json(['error' => 'Resource not found'],404);
    }




    public function getMenuClass($menu) {
        //get domain
        $menu = Menu::find($menu);
        if($menu) {
            //get all menuClasses under domain
            $menuClasses = MenuClass::where("menu_id",$menu->id)->get();
            return response()->json([
                "data" => [
                    "menuClasses" => $menuClasses->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }
}
