<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soldcards extends Model
{
    //

    protected $fillable = [
        'transaction_id', 'user_id', 'gateway_name','amount','charge','net_amount','status','account',
    ];

    public function user(){

        return $this->belongsTo('App\Soldcards');

    }



}
