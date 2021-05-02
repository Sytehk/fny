<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ptc extends Model
{
    //
    protected $fillable = [
        'title', 'details', 'ad_link','rewards','duration','membership_id','status','user_id','order_id','type','hit','count',
    ];

    public function membership(){

        return $this->belongsTo('App\Membership');

    }
    public function adverts(){

        return $this->hasMany('App\Advert');

    }
    public function user(){

        return $this->belongsTo('App\User');

    }
    public function order(){

        return $this->belongsTo('App\Order');

    }

}
