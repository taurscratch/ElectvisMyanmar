<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Census extends Model
{

    protected $keyType = 'string';


    public function Area()
    {
        return $this->belongsTo('App\Area');
    }
}
