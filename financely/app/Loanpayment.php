<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


class Loanpayment extends Model
{
    //
    protected $fillable = [
        'loancode', 'user_id', 'loanplan','amount','status',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }




}
