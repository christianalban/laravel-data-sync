<?php

namespace Alban\LaravelDataSync\Support\Pipe;

abstract class Pipe
{
    private array $params;

    public function __construct(
        ...$params
    ) {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
