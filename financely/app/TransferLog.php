<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TransferLog extends Model
{
    //
    protected $fillable = [
        'reference', 'user_id', 'name','balance','email','amount','charge','net_amount','status','type',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }
}
