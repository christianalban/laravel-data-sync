<?php

namespace Alban\LaravelDataSync\Support\Pipe;

class ArrayPipe extends Pipe
{
    public function transform(mixed $data, string $separator): mixed
    {
        return explode($separator, $data);
    }
}
