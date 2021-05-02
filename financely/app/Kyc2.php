<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kyc2 extends Model
{
    //
    protected $fillable = [

        'name', 'user_id','status','idnumber','fphoto','bphoto','expdate','ssn',

    ];

    public function user(){

        return $this->belongsTo('App\User');

    }
    public function getFphotoAttribute($fphoto){

        return asset($fphoto);

    }
      public function getBphotoAttribute($bphoto){

        return asset($bphoto);

    }
}
