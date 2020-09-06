<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
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

    public function Census()
    {
        return $this->hasOne('App\Census');
    }
    
  
}
