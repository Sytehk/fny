<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


class Userloan extends Model
{
    //
    protected $fillable = [
        'loancode','tenure', 'user_id', 'loanid','amount','loanplan','topay','balance','status','paid',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }




}
