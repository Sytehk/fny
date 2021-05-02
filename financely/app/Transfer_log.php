<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


class Transfer_log extends Model
{
    //
    protected $fillable = [
        'reference', 'user_id', 'name', 'email', 'gateway_name','amount','charge','net_amount','status','details',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }




}
