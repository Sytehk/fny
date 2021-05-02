<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buycards extends Model
{
    //

    protected $fillable = [
        'transaction_id', 'user_id', 'gateway_name','equivalent','amount','balance_before','balance_after','type','status','account',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }



}
