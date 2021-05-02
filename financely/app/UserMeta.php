<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    //
    protected  $fillable =[

        'userId',
        'notify_admin',
        'newsletter',
        'unusual',
        'save_activity',
        'pwd_chng'
    ];

    public function user(){

        return $this->belongsTo('App\User', 'userId', 'id');

    }
}
