<?php

namespace Alban\LaravelDataSync\Support\Parser;

use Illuminate\Support\Collection;

abstract class Parser
{
    public abstract function parse(Collection $data): Collection;
}
