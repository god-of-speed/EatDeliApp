<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag'];

    /**
     * many to many
     */
    public function domains() {
        return $this->belongsToMany('App\Domain');
    }

    /**
     * many to many
     */
    public function products() {
        return $this->belongsToMany('App\Product');
    }

    /**
     * one to many
     */
    public function domain_tags() {
        return $this->hasMany('App\Domain_Tag');
    }

    /**
     * one to many
     */
    public function product_tags() {
        return $this->hasMany('App\Product_Tag');
    }
}
