<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $table = 'product_tags';
    /**
     * @var array
     */
    protected $fillable = ['product_id','tag_id'];

    /**
     * many to one
     */
    public function product() {
        return $this->belongsTo('App\Product');
    }

    /**
     * many to one
     */
    public function tag() {
        return $this->belongsTo('App\Tag');
    }
}
