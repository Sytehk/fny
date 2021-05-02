<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageFs extends Model
{
    //

    protected $fillable = [
        'title', 'content', 'status','slug',
    ];


}
