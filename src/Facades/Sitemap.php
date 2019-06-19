<?php

namespace Virtualorz\Sitemap\Facades;

use Illuminate\Support\Facades\Facade;

class Sitemap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sitemap';
    }
}
