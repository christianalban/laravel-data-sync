<?php

namespace Alban\LaravelDataSync\Support;

use Alban\LaravelDataSync\Support\Classifier\Classifier;
use Alban\LaravelDataSync\Support\Classifier\PropMatchClassifier;
use Alban\LaravelDataSync\Support\Parser\Parser;

abstract class Sync
{
    private array $compareData;

    public function parser(): Parser | null
    {
        return null;
    }

    public function compareWith(array $data): self
    {
        $this->compareData = $data;

        return $this;
    }

    public function getCompareData(): array
    {
        return $this->compareData;
    }

    public function classifier(): Classifier | null
    {
        return null;
    }

    public function propMatch(string $key): PropMatchClassifier
    {
        return new PropMatchClassifier($key);
    }
}
