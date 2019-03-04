<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
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
    protected $fillable = ['title','description','domain_id'];

    /**
     * many to one
     */
    public function domain() {
        return $this->belongsTo('App\Domain');
    }

    /**
     * one to many
     */
    public function menuClasses() {
        return $this->hasMany('App\MenuClass');
    }

    /**
     * many to one
     */
    public function products() {
        return $this->hasMany('App\Product');
    }
}
