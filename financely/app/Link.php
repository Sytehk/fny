<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //
    protected $fillable = [
        'title', 'details', 'link','rewards','membership_id','status','user_id','order_id','type',
    ];

    public function membership(){

        return $this->belongsTo('App\Membership');

    }

    public function shares(){

        return $this->hasMany('App\Share');

    }

    public function user(){

        return $this->belongsTo('App\User');

    }

    public function order(){

        return $this->belongsTo('App\Order');

    }
}
