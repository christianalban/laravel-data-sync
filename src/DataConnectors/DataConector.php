<?php

namespace Alban\LaravelDataSync\DataConnectors;

interface DataConector
{
    public function getData(): array;
}
