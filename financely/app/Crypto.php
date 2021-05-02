<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    //
    protected $fillable = [
        'transaction_id',
        'user_id',
        'amount',
        'amount2',
        'charge',
        'net_amount',
        'status',
        'details',
        'currency1',
        'currency2',
        'gateway_id',
        'payment',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }
    public function gateway(){

        return $this->belongsTo('App\Gateway');

    }
}
