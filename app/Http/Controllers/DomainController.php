<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Menu;
use App\Order;
use App\Domain;
use App\Product;
use App\DomainTag;
use App\MenuClass;
use Illuminate\Http\Request;
use App\Service\GeneratorService;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->only('store','updateDomain','destroy','edit');
        $this->middleware('authorize')->only('updateDomain','destroy','edit');
    }


    

    public function domains() {
        //get all domains
        $domains = Domain::paginate(20);
        return response()->json(["data" => [
                "domains" => $domains->toArray()
            ]
        ],200);
    }




    public function show($domain) {
        //get domain info
        $domain = Domain::find($domain);
        if($domain) {
            //check if user is the admin of the domain
            $isAdmin = Auth::guard('api')->id() == $domain->user()->first()->id;
            //get tags
            $tagsArr = DomainTag::where('domain_id',$domain->id)->get();
            $tags = [];
            foreach($tagsArr as $tag) {
                $tag = Tag::find($tag->tag()->first()->id);
                $tags[] = $tag;
            }
             
            return response()->json([
                "data" => [
                    'domain' => $domain->toArray(),
                    'tags' => $tags,
                    'isAdmin' => $isAdmin
                    ]
                ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    public function store(Request $request,GeneratorService $generatorService) {
        //get all input
        $data = $request->all();
        //validate inputs
        $validator = $request->validate([
            'brandname' => 'required|string|max:255|unique:domains',
            'description' => 'required|string',
            'tags' => 'required|string',
            'brandImage' => 'required|image|mimetypes:image/png,image/jpg,image/jpeg|max:2048'
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
        //create domain
        $filename = null;
        if($request->file('brandImage')->isValid()) {
            $filename = $generatorService->makeName('image',8).'.'.$data['brandImage']->guessExtension();
            while(Domain::where('brandImage','/images/domain/brandImage/'.$filename)->first()) {
                $filename = $generatorService->makeName('image',8).'.'.$data['brandImage']->guessExtension();
            }
       
            $path = public_path().'/images/domain/brandImage';
            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $data['brandImage']->move($path,$filename);
            $filename = '/images/domain/brandImage/'.$filename;
        } 

        //create new domain
        $domain = Domain::create([
            'brandname' => ucwords($data['brandname']),
            'description' => $data['description'],
            'brandImage' => $filename,
            'user_id' => Auth::guard('api')->id()
        ]);

        if($domain) {
            foreach($inputTags as $tag) {
                $dataTag = Tag::where('tag',trim($tag))->first();
                if(!DomainTag::where([
                    ['domain_id',$domain->id],
                    ['tag_id',$dataTag->id]
                ])->first()) {
                    //create domainTag
                    DomainTag::create([
                        'domain_id' => $domain->id,
                        'tag_id' => $dataTag->id
                    ]);
                }
            }
            //get tags
            $tagsArr = DomainTag::where('domain_id',$domain->id)->get();
            $tags = [];
            foreach($tagsArr as $tag) {
                $tag = Tag::find($tag->tag()->first()->id);
                $tags[] = $tag;
            }
            
            return response()->json([
                'data' => [
                    'domain' => $domain->toArray(),
                    'tags' => $tags
                    ]
                ],200);
        }
        return response("Ooops! error occured please try again.",500)->header('Content-Type','application/json');
    }




    public function index($domain) {
        //get domain
        $domain = Domain::find($domain);
        if($domain) {
            //get set menu
            $products = Product::where([
                        ['domain_id',$domain->id],
                        ['isSet',true]
                    ])->paginate(15);
            //get all tags related to domain
            $domainTags = DomainTag::where('domain_id',$domain->id);
            $tagsArr = [];
                foreach($domainTags as $tag) {
                    $tagsArr[] = $tag->tag()->tag;
                }
            //get tags
            $tags = DomainTag::where('domain_id',$domain->id)->get();
            return response()->json([
                'data' => [
                    'products' => $products->toArray(),
                    'tags' => $tags->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }



    
    public function edit($domain) {
        //get domain
        $domain = Domain::find($domain);
        if($domain) {
            //get tags
            $domainTags = DomainTag::where('domain_id',$domain->id)->get();
            $tags   =   [];
            foreach($domainTags as $tag)   {
                $tags[] =   $tag->tag()->first(); 
            }

            return response()->json([
                "data" => [
                    "domain" => $domain->toArray(),
                    "tags" => $tags
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }




    public function updateDomain(Request $request,$domain) {
        //get domain
        $domain = Domain::find($domain);
        if($domain) {
            
            //get all input
            $data = $request->all();
            //validate inputs
            $validator = $request->validate([
                'description' => 'required|string',
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
            $domainTags = DomainTag::where('domain_id',$domain->id)->get();
            $domainTagsArr = [];
            foreach($domainTags as $domainTag) {
                $domainTagsArr[] = $domainTag->tag()->first()->id;
            }
            $databaseTag = [];
            foreach($inputTags as $inputTag) {
                foreach($tags as $tag) {
                    if($tag->tag == trim($inputTag)) {
                        $databaseTag[] = $tag->id;
                    }
                }
            }

            foreach($domainTagsArr as $arr) {
                if(!in_array($arr,$databaseTag)) {
                    $domain_tag = DomainTag::where('tag_id',$arr)->delete();
                }
            }

            //create new domain
            $updatedDomain = $domain->update([
                'description' => $data['description']
            ]);

            if($domain) {
                foreach($inputTags as $tag) {
                    $dataTag = Tag::where('tag',trim($tag))->first();
                    if(!DomainTag::where([
                        ['domain_id',$domain->id],
                        ['tag_id',$dataTag->id]
                    ])->first()) {
                        //create domain_tag
                        DomainTag::create([
                            'domain_id' => $domain->id,
                            'tag_id' => $dataTag->id
                        ]);
                    }
                }
                //get tags
                $tags = DomainTag::where('domain_id',$domain->id)->get();
                return response()->json([
                    'data' => [
                        'domain' => $domain->toArray(),
                        'tags' => $tags->toArray()
                    ]
                ],200);
            }
            return response()->json(['error' => 'Server error'],500);
        }
       return response()->json(['error' => 'Resoursce not found'],404); 
    }




    public function destroy($domain) {
        //get domain
        $domain = Domain::find($domain);
        if($domain) {
            $domain->delete();
            return response()->json(['data' => true],200);
        }
        return response()->json(['error' => 'Resource not found'],404); 
    }




    public function getDomainMenu($domain) {
        //get domain
        $domain = Domain::find($domain);
        if($domain) {
            //get all menu under domain
            $menus = Menu::where('domain_id',$domain->id)->get();
            return response()->json([
                "data" => [
                    "menus" => $menus->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }




    public function getDomainMenuClass($domain) {
        //get domain
        $domain = Domain::find($domain);
        if($domain) {
            //get all menuClasses under domain
            $menuClasses = MenuClass::where("domain_id",$domain->id)->get();
            return response()->json([
                "data" => [
                    "menuClasses" => $menuClasses->toArray()
                ]
            ],200);
        }
        return response()->json(['error' => 'Resource not found'],404);
    }
}
