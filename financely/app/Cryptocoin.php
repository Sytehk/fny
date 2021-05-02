<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cryptocoins extends Model
{
    //
    protected $fillable = [
        'name','price','sell','available','status',
    ];

    public function invests(){

        return $this->hasMany('App\Invest');

    }

    public function style(){

        return $this->belongsTo('App\Style');

    }
}
