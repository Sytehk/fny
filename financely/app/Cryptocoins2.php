<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cryptocoins2 extends Model
{
    //

    protected $fillable = [
        'name', 'price','available','status',
    ];

    public function index(){

        return $this->belongsTo('App\User');

    }




}
