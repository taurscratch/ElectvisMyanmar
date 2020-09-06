<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionalResult extends Model
{
    public function RegionalHouse()
    {
        return $this->belongsTo('App\RegionalHouse');
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
