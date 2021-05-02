<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    //
    protected $fillable = [
        'title', 'user_id', 'body','status','file','priority',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }

}
