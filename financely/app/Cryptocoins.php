<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cryptocoins extends Model
{
    //

    protected $fillable = [
        'name','sell', 'details','price','available','status',
    ];

    public function index(){

        return $this->belongsTo('App\User');

    }




}
