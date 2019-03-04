<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * one to many
     */
    public function users() {
        return $this->hasMany('App\User');
    }
}
