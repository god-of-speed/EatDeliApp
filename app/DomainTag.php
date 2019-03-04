<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomainTag extends Model
{
    protected $table = 'domain_tags'; 

    /**
     * @var array
     */
    protected $fillable = ['domain_id','tag_id'];

    /**
     * many to one
     */
    public function domain() {
        return $this->belongsTo('App\Domain');
    }

    /**
     * many to one
     */
    public function tag() {
        return $this->belongsTo('App\Tag');
    }
}
