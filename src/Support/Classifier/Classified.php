<?php

namespace Alban\LaravelDataSync\Support\Classifier;

use Illuminate\Support\Collection;

class Classified
{
    public function __construct(
        private Collection $toCreate,
        private Collection $toUpdate,
        private Collection $toDelete
    ) {}

    public function getToCreate(): Collection
    {
        return $this->toCreate;
    }

    public function getToUpdate(): Collection
    {
        return $this->toUpdate;
    }

    public function getToDelete(): Collection
    {
        return $this->toDelete;
    }
}
