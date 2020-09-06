<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $keyType = 'string';

    public function NationalityResults()
    {
        return $this->hasMany('App\NationalityResult');
    }
    public function RegionalResults()
    {
        return $this->hasMany('App\RegionalResult');
    }
    public function RepresentativeResults()
    {
        return $this->hasMany('App\RepresentativeResult');
    }
}
