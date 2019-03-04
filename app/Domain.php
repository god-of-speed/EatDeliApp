<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['brandname','description','brandImage','user_id'];

    /**
     * many to one
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * many top many
     */
    public function tags() {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * one to many
     */
    public function menus() {
        return $this->hasMany('App\Menu');
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
    public function products() {
        return $this->hasMany('App\Product');
    }



    /**
     * one to many
     */
    public function orders() {
        return $this->hasMany('App\Order');
    }
}
