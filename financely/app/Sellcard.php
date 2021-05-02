<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sellcard extends Model
{
    //
    protected $dates = ['dob'];
    protected $fillable = [

        'name','value', 'user_id', 'number','front','back','dob','status',

    ];
    public function user(){

        return $this->belongsTo('App\User');

    }
    public function getFrontAttribute($front){

        return asset($front);

    }
    public function getBackAttribute($back){

        return asset($back);

    }
}
