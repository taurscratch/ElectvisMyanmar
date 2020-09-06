<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OperatorHelper
{
    public static function SignIt($number)
    {
        if ($number > 0) {
            return '+';
        }
    }
}
