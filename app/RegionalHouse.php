<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionalHouse extends Model
{
    public function Area()
    {
        return $this->belongsTo('App\RegionalHouse');
    }
    public function RegionalResults()
    {
        return $this->hasMany('App\RegionalResult');
    }
    public function Region()
    {
        return $this->belongsTo('App\Region');
    }
}
