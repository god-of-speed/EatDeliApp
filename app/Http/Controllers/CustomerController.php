<?php

namespace App\Http\Controllers;

use App\Order;
use App\Domain;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('authorize')->only('getDomainOrder','getDomainOrderHistory');
    }


    public function setOrder(Request $request) {
        //get cart from session
        $carts = $request->all();
        $hasError = false;
        for($i = 0; $i<count($carts); $i++) {
            $product = Product::find($carts[$i]['product']['id']);
            if($product) {
                $order = Order::create([
                    'domain_id'=>$product->domain()->first()->id,
                    'buyer_id'=>Auth::guard('api')->id(),
                    'product_id'=> $product->id,
                    'status' => 'pending',
                    'paid' => false,
                    'delivered' => false,
                    'received' => false,
                    'quantity' => (int)$carts[$i]['quantity'],
                    'price' => (int)$carts[$i]['quantity'] * $product->price
                ]);
                if(!$order) {
                   $hasError = true; 
                }
            }
        }
        if(!$hasError) {
            return response()->json([
                "data" => true
            ],200);
        }else{
            return response()->json(['error' => 'Resource not found'],404);
        }
    }



    public function getOrder() {
        //get user order
        $orders = Order::where([
                    ['buyer_id',Auth::guard('api')->id()],
                    ['status','!=','concluded'],
                    ['paid',false]
                ])->latest()->get();
        //include product info
        $orderArr = [];
        foreach($orders as $order) {
            //get product for each order
            $product = Product::find($order->product_id);
            $orderArr[] = (object) [
                "product" => $product,
                "order" => $order
            ];
        }

        return response()->json([
            "data" => [
                "orders" => $orderArr
            ]
        ],200);
    }



    public function getOrderHistory() {
        //get user order
        $orders = Order::where('buyer_id',Auth::guard('api')->id())->latest()->get();
        return response()->json([
            "data" => [
                "orders" => $orders->toArray()
            ]
        ],200);
    }




    public function getDomainOrder($domain) {
        $domain = Domain::find($domain);
        if($domain) {
            //get user order
            $orders = Order::where([
                ['domain_id',$domain->id],
                ['status','!=','concluded']
            ])->latest()->get();
            return response()->json([
                'data' => [
                    'orders' => $orders->toArray()
                ]
                ],200);
        }
        return response()->json(['error' => 'Resource not found'],404); 
    }



    public function getDomainOrderHistory($domain) {
        $domain = Domain::find($domain);
        if($domain) {
            //get user order
            $orders = Order::where('domain_id',$domain->id)->latest()->get();
            return response()->json([
                'data' => [
                    'orders' => $orders->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }




    public function changeOrderStatus($order,$status) {
        $order = Order::find($order);
        if($order) {
            //get order
            if($order->buyer()->first()->id == Auth::guard('api')->id()) {
                if($status == 'received') {
                    $order->received = true;
                    if($order->delivered) {
                        $order->status = "concluded";
                    }
                    $order->save();
                }elseif($status == "cancelled") {
                    $order->status = "cancelled";
                    $order->save();
                }
                
                //get user order
                $orders = Order::where([
                    ['buyer_id',Auth::guard('api')->id()],
                    ['status','!=','concluded'],
                    ['paid',false]
                ])->latest()->get();
    
                return response()->json([
                    "data" => [
                        "orders" => $orders->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Unauthorised access'],403);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function changeAdminOrderStatus($order,$status) {
        $order = Order::find($order);
        if($order) {
            //get order
            if(Auth::guard('api')->id() == $order->domain()->first()->user()->first()->id) {
                if($status == 'delivered') {
                    $order->delivered = true;
                    if($order->received) {
                        $order->status = "concluded";
                    }
                    $order->save();
                }elseif($status == "cancelled") {
                    $order->status = "cancelled";
                    $order->save();
                }

                //get user order
                $orders = Order::where([
                    ['domain_id',$order->domain()->first()->id],
                    ['status','!=','concluded']
                ])->latest()->get();

                return response()->json([
                    'data' => [
                        'orders' => $orders->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Unautorized Access'],403);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }
}
