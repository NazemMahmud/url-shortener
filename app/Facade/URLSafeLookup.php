<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class URLSafeLookup extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'is-url-safe';
    }

}
