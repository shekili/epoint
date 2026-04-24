<?php

namespace shekili\epoint\Facades;

use Illuminate\Support\Facades\Facade;


class Epoint extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'epoint';
    }
}
