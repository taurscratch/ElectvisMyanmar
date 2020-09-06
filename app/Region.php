<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $keyType = 'string';

    public function NationalityHouses()
    {
        return $this->hasMany('App\NationalityHouse');
    }
    public function RegionalHouses()
    {
        return $this->hasMany('App\RegionalHouse');
    }
    public function RepresentativeHouses()
    {
        return $this->hasMany('App\RepresentativeHouse');
    }
 
}
