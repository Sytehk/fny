<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    //
    protected  $fillable =[

        'user_id',
        'link_id',
        'date',
        'status'

    ];

    public function user(){

        return $this->belongsTo('App\User');
    }

    public function link(){

        return $this->belongsTo('App\Link');

    }
}
