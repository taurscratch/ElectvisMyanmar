<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NationalityHouse extends Model
{

    protected $keyType = 'string';

    public function Area()
    {
        return $this->belongsTo('App\Area');
    }
    public function NationalityResults()
    {
        return $this->hasMany('App\NationalityResult','nationalityhouse_id');
    }
    public function Region()
    {
        return $this->belongsTo('App\Region');
    }
}
