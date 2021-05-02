<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyunitcoins extends Model
{
    //

    protected $fillable = [
        'transaction_id', 'coinid','account', 'user_id','name', 'units', 'amount', 'status',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }



}
