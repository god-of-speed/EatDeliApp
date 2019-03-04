<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable; use SoftDeletes;

    public function generateToken() {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

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
    protected $fillable = [
        'firstname','lastname', 'username', 'password','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * one to many
     * 
     */
    public function role() {
        return $this->belongsTo('App\Role');
    }

    /**
     * one to many
     */
    public function domains() {
        return $this->hasMany('App\Domain');
    }

    /**
     * one to many
     */
    public function orders() {
        return $this->hasMany('App\Order','buyer_id');
    }
}
