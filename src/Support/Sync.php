<?php

namespace Alban\LaravelDataSync\Support;

use Alban\LaravelDataSync\Support\Classifier\Classifier;
use Alban\LaravelDataSync\Support\Classifier\PropMatchClassifier;
use Alban\LaravelDataSync\Support\Parser\Parser;
use Alban\LaravelDataSync\Support\Synchronizer\Synchronizer;

abstract class Sync
{
    public function parser(): Parser | null
    {
        return null;
    }

    public function classifier(): Classifier | null
    {
        return null;
    }

    public function propMatch(string $key): PropMatchClassifier
    {
        return new PropMatchClassifier($key);
    }

    public function synchronizer(): Synchronizer | null
    {
        return null;
    }

    public function uniqueJobId(array $data): string
    {
        return uniqid();
    }
}
