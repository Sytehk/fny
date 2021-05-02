<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $fillable = [
        'style_id', 'name','minimum','maximum','percentage','repeat','status','start_duration',
    ];

    public function invests(){

        return $this->hasMany('App\Invest');

    }

    public function style(){

        return $this->belongsTo('App\Style');

    }
}
