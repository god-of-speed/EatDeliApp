<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Menu;
use App\Order;
use App\Domain;
use App\Product;
use App\MenuClass;
use App\ProductTag;
use Illuminate\Http\Request;
use App\Service\GeneratorService;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->except('index','show');
        $this->middleware('authorize')->only('store');
        $this->middleware('authorizeProduct')->except('index','show','store','productOrder');
    }


    public function show($product) {
        //get domain info
        $product = Product::find($product);
        if($product) {
            //check if user is the admin of the domain
            $isAdmin = Auth::guard('api')->id() == $product->domain()->first()->user()->first()->id;

            return response()->json([
                "data" => [
                    "product" => $product->toArray(),
                    "isAdmin" => $isAdmin
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function store(Request $request,GeneratorService $generatorService,$domain) {
        $domain = Domain::find($domain);
        if($domain) { 
            //set variables
            $menuId = null;
            $menuClassId = null;
            $query = null;
            //get query
            $query = $request->query('menu');
            if($query) {
                $menu = Menu::find($query);
                if($menu) {
                    $menuId = $menu->id;
                }
            }else{
                $query = $request->query('menu_class');
                if($query) {
                    $menuClass = MenuClass::find($query);
                    if($menuClass) {
                        $menuId = $menuClass->id;
                    }
                }
            }
            $data = $request->all();
            //validate input
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|file|image|mimetypes:image/png,image/jpeg,image/jpg|max:2048',
                'description' => 'nullable|string',
                'price' => 'required|string',
                'currency' => 'required|string',
                'tags' => 'required|string'
            ]);
            dump($data['price']);die;
    
            //create domain
            $filename = null;
            if($request->file('image')->isValid()) {
                $filename = $generatorService->makeName('image',8).'.'.$data['image']->guessExtension();
                while(Product::where('image','/images/products/'.$domain->brandname.'/'.$filename)->first()) {
                    $filename = $generatorService->makeName('image',8).'.'.$data['image']->guessExtension();
                }
                $path = public_path().'/images/products/'.$domain->brandname;
                if(!file_exists($path)) {
                    mkdir($path,0777,true);
                }
                $data['image']->move($path,$filename);
                $filename = "/images/products/".$domain->brandname.'/'.$filename;
            }
            //get Tags
            $tags = Tag::all();
            $tagsArr = [];
            foreach($tags as $tag) {
                    $tagsArr[] = $tag->tag;
            }
            //make an array
            $inputTags = explode(',',$data['tags']);
            foreach($inputTags as $tag) {
                if(!in_array(trim($tag),$tagsArr)) {
                    Tag::create([
                        'tag' => strtolower(trim($tag))
                    ]);
                }
            }
    
            $product = Product::create([
                'name' => ucwords($data['name']),
                'image' => $filename,
                'description' => $data['description'],
                'oldPrice' => $data['price'],
                'price' => $data['price'],
                'currency' => $data['currency'],
                'isSet' => true,
                'domain_id' => $domain->id,
                'menu_id' => $menuId,
                'menuClass_id' => $menuClassId
            ]);
    
            if($product) {
                foreach($inputTags as $tag) {
                    $dataTag = Tag::where('tag',trim($tag))->first();
                    if(!ProductTag::where([
                        ['product_id',$product->id],
                        ['tag_id',$dataTag->id]
                    ])->first()) {
                        //create domain_tag
                        ProductTag::create([
                            'product_id' => $product->id,
                            'tag_id' => $dataTag->id
                        ]);
                    }
                }
                //get tags
                $productTags = ProductTag::where('product_id',$product->id)->get();
                return response()->json([
                    "data" => [
                        "product" => $product->toArray(),
                        
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function index($product) {
        //get product
        $product = Product::find($product);
        if($product) {
            //check if user is admin
            $isAdmin = Auth::guard('api')->id() == $product->domain()->first()->user()->first()->id;
            //get tags
            $productTags = ProductTag::where('product_id',$product->id)->get();
            return response()->json([
                "data" => [
                    "product" => $product->toArray(),
                    "tags" => $productTags->toArray(),
                    "isAdmin" => $isAdmin
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }




    public function edit($product) {
        //get domain
        $product = Product::find($product);
        if($product) {
            //get tags
            $productTags = ProductTag::where('product_id',$product->id)->get();
            $tags   =   [];
            foreach($productTags as $tag)   {
                $tags[] =   $tag->tag()->first(); 
            }

            return response()->json([
                "data" => [
                    "product" => $product->toArray(),
                    "tags" => $tags
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function update(Request $request,$product) {
        //get product
        $product = Product::find($product);
        if($product) {
            //get all input
            $data = $request->all();
            //validate input
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'tags' => 'required|string'
            ]);

            //get Tags
            $tags = Tag::all();
            $tagsArr = [];
            foreach($tags as $tag) {
                $tagsArr[] = $tag->tag;
            }
            //make an array
            $inputTags = explode(',',$data['tags']);
            foreach($inputTags as $tag) {
                if(!in_array(trim($tag),$tagsArr)) {
                    Tag::create([
                        'tag' => strtolower(trim($tag))
                    ]);
                }
            }
            //get domain tags
            $productTags = ProductTag::where('product_id',$product->id)->get();
            $productTagsArr = [];
            foreach($productTags as $productTag) {
                $productTagsArr[] = $productTag->tag()->first()->id;
            }
            $databaseTag = [];
            foreach($inputTags as $inputTag) {
                foreach($tags as $tag) {
                    if($tag->tag == trim($inputTag)) {
                        $databaseTag[] = $tag->id;
                    }
                }
            }
            foreach($productTagsArr as $arr) {
                if(!in_array($arr,$databaseTag)) {
                    $product_tag = ProductTag::where('tag_id',$arr)->first()->delete();
                }
            }

            $productUpdate = $product->update([
                'name' => ucwords($data['name']),
                'description' => $data['description']
            ]);

            if($product) {
                foreach($inputTags as $tag) {
                    $dataTag = Tag::where('tag',trim($tag))->first();
                    if(!ProductTag::where([
                        ['product_id',$product->id],
                        ['tag_id',$dataTag->id]
                    ])->first()) {
                        //create domain_tag
                        ProductTag::create([
                            'product_id' => $product->id,
                            'tag_id' => $dataTag->id
                        ]);
                    }
                }
                //get tags
                $tags = ProductTag::where('product_id',$product->id)->get();
                return response()->json([
                    "data" => [
                        "product" => $product->toArray(),
                        "tags" => $tags->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
        return response()->json(['error' => 'Resource not found'],404); 
    }



    public function updatePrice(Request $request,$product) {
        //get product
        $product = Product::find($product);
        if($product) {
            $data = $request->all();
            //validate
            $validator = $request->validate([
                'price' => 'required|integer',
                'currency' => 'required|string'
            ]);

            //update product
            $productUpdate = $product->update([
                'price' => $data['price'],
                'oldPrice' => $product->oldPrice,
                'currency' => $data['currency']
            ]);
            //get tags
            $tags = ProductTag::where('product_id',$product->id)->get();
            if($product) {
                return response()->json([
                    "data" => [
                        "product" => $product->toArray(),
                        "tags" => $tags->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function destroy($product) {
        //get domain
        $product = Product::find($product);
        if($product) {
            $product->delete();
            return response()->json(['data' => true],404);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function productOrder($productId) {
        //get product
        $product = Product::find($productId);
        if($product) {
            $order = Order::create([
                'domain_id'=>$product->domain()->first()->id,
                'buyer_id'=>Auth::guard('api')->id(),
                'product_id'=> $product->id,
                'status' => 'pending',
                'paid' => false,
                'delivered' => false,
                'received' => false,
                'quantity' => 1,
                'price' => $product->price
            ]);

            if($order) {
                return response()->json(["data" => true],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }
}
