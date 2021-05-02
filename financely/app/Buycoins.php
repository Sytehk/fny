<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buycoins extends Model
{
    //

    protected $fillable = [
        'transaction_id', 'user_id','account','amount','type','status',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }



}
