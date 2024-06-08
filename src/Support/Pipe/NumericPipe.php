<?php

namespace Alban\LaravelDataSync\Support\Pipe;

class NumericPipe extends Pipe
{
    public function transform(mixed $data): mixed
    {
        return (float) $data;
    }
}
