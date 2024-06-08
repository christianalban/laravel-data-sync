<?php

return [
    'startRow' => 1,

    'pipes' => [
        'numeric' => \Alban\LaravelDataSync\Support\Pipe\NumericPipe::class,
        'integer' => \Alban\LaravelDataSync\Support\Pipe\IntegerPipe::class,
        'boolean' => \Alban\LaravelDataSync\Support\Pipe\BooleanPipe::class,
        'array' => \Alban\LaravelDataSync\Support\Pipe\ArrayPipe::class,
    ],
];
