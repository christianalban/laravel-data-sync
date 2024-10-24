<?php

namespace Alban\LaravelDataSync\Support\Pipe;

class ArrayPipe extends Pipe
{
    public function transform(mixed $data, string $separator): mixed
    {
        $dataArray = explode($separator, $data);

        return array_map(function ($item) {
            return trim($item);
        }, $dataArray);
    }
}
