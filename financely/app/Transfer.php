<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    //
    protected $fillable = [
        'reference', 'user_id', 'receipt','amount','charge','net_amount','status','verify','type','counter'
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }
}
