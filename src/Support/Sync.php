<?php

namespace Alban\LaravelDataSync\Support;

use Alban\LaravelDataSync\Support\Parser\Parser;

abstract class Sync
{
    public function parser(): Parser | null
    {
        return null;
    }
}
