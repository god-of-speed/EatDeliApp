<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuClass extends Model
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
    protected $fillable = ['title','description','menu_id','domain_id'];

    /**
     * many to one
     */
    public function menu() {
        return $this->belongsTo('App\Menu');
    }

    /**
     * one to many
     */
    public function products() {
        return $this->hasMany('App\Product');
    }

    /**
     * many to one
     */
    public function domain() {
        return $this->belongsTo('App\Domain');
    }
}
