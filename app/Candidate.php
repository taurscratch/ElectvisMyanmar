<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{

    protected $keyType = 'string';
    public function NationalityResults()
    {
        return $this->hasMany('App\NationalityResults');
    }
    public function RegionalResults()
    {
        return $this->hasMany('App\RegionalResults');
    }
    public function RepresentativeResults()
    {
        return $this->hasMany('App\RepresentativeResults');
    }
}
