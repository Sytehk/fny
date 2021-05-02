<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'avatar',
        'mobile',
        'occupation',
        'address',
        'address2',
        'city',
        'state',
        'postcode',
        'country',
        'facebook',
        'about',
        'main_balance',
        'deposit_balance',
        'referral_balance',

    ];


    public function user(){

        return $this->belongsTo('App\User');


    }
    public function getAvatarAttribute($avatar){

        return asset($avatar);

    }
    
     public function reflink(){
        return $this->belongsTo('App\Reflink');
    }
    public function childs() {
        return $this->hasMany('App\ReferralFs','parent_id','id') ;
    }
    public function uplines()
    {
        $this->belongsToMany('App\ReferralFs','parent_id','id') ;
    }

}
