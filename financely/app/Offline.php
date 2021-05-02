<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offline extends Model
{

    protected $fillable = [

        'name', 'image', 'account','fixed','percent','val1','val2','status','details',

    ];

    public function getFeaturedAttribute($image){

        return asset($image);

    }

}
