<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
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
        'domain_id',
        'product_id',
        'buyer_id',
        'status',
        'paid',
        'delivered',
        'received',
        'quantity',
        'price'
    ];



    /**
     * many to one
     */
    public function domain() {
        return $this->belongsTo('App\Domain');
    }


    /**
     * many to one
     */
    public function product() {
        return $this->belongsTo('App\Product');
    }



    /**
     * many to one
     */
    public function buyer() {
        return $this->belongsTo('App\User');
    }
}
