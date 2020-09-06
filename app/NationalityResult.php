<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NationalityResult extends Model
{

    protected $keyType = 'string';

    public function NationalityHouse()
    {
        return $this->belongsTo('App\NationalityHouse','nationalityhouse_id');
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
