<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{

    protected $fillable = [
        'name','price','hit','status','duration','type'
    ];

    public function orders(){

        return $this->hasMany('App\Order');

    }
}
