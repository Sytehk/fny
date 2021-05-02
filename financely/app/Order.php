<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'scheme_id', 'user_id', 'turn','status','totalHit','url','code','type','title','membership_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function scheme(){
        return $this->belongsTo('App\Scheme');

    }
    public function membership(){
        return $this->belongsTo('App\Membership');

    }
    public function ptcs(){

        return $this->hasMany('App\Ptc');

    }
    public function ptc(){

        return $this->hasOne('App\Ptc');

    }
}
