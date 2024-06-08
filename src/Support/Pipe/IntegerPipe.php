<?php

namespace Alban\LaravelDataSync\Support\Pipe;

class IntegerPipe extends Pipe
{
    public function transform(mixed $data): mixed
    {
        return (int) $data;
    }
}
