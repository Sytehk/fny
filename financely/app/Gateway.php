<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    //

    protected $fillable = [

        'name', 'image', 'account','fixed','percent','mode','val1','val2','status','details','val3',
    ];
    public function getFeaturedAttribute($image){

        return asset($image);

    }
    public function cryptos(){

        return $this->hasMany('App\Crypto');

    }
}
