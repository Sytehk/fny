<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sellunitcoins extends Model
{
    //

    protected $fillable = [
        'transaction_id', 'sell', 'balance', 'user_id','name', 'units', 'amount', 'status',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }



}
