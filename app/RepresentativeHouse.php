<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepresentativeHouse extends Model
{
    public function Area()
    {
        return $this->belongsTo('App\Area');
    }

    public function RepresentativeResults()
    {
        return $this->hasMany('App\RepresentativeResult');
    }

    public function Region()
    {
        return $this->belongsTo('App\Region');
    }
}
