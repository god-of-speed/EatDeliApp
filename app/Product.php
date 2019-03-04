<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'name','image','description','oldPrice','domain_id','price','currency','isSet','menuClass_id','menu_id'
    ];

    /**
     * many to one
     */
    public function menuClass() {
        return $this->belongsTo('App\MenuClass');
    }

    /**
     * many to one
     */
    public function domain() {
        return $this->belongsTo('App\Domain');
    }

    /**
     * many to many
     */
    public function tags() {
        return $this->belongsToMany('App\Tag');
    } 

    /**
     * one to many
     */
    public function product_tags() {
        return $this->hasMany('App\Product_Tag');
    }

    /**
     * one to many
     */
    public function orders() {
        return $this->hasMany('App\Order');
    }
}
