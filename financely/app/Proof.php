<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    public $timestamps = true;
    //
    protected $fillable = [

        'name',
        'gateway',
        'amount',
        'type',
        'status',
        'updated_at',
        'created_at',

    ];
}
