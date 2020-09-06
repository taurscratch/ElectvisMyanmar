<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepresentativeResult extends Model
{
    public function RepresentativeHouse()
    {
        return $this->belongsTo('App\RepresentativeHouse');
    }
    public function Party()
    {
        return $this->belongsTo('App\Party');
    }
    public function Candidate()
    {
        return $this->belongsTo('App\Candidate');
    }
}
