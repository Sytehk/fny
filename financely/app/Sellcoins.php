<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sellcoins extends Model
{
    //
    protected $dates = ['dob'];
    protected $fillable = [

        'type','trans_id','value', 'user_id', 'number','front','amount','photo', 'pick', 'status',

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
