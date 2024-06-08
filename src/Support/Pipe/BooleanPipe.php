<?php

namespace Alban\LaravelDataSync\Support\Pipe;

class BooleanPipe extends Pipe
{
    public function transform(mixed $data): mixed
    {
        if (strtolower($data) === 'true') {
            return true;
        }

        if (strtolower($data) === 'false') {
            return false;
        }

        return (bool) $data;
    }
}
