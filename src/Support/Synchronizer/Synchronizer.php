<?php

namespace Alban\LaravelDataSync\Support\Synchronizer;

use Alban\LaravelDataSync\Jobs\SyncProcess;
use Alban\LaravelDataSync\Support\Classifier\Classified;
use Alban\LaravelDataSync\Support\Sync;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

abstract class Synchronizer {
    public function sync(Sync $sync, Classified $classified): Collection {

        $preparedCreated = $this->prepareSync('create', $sync, $classified->getToCreate());

        $preparedUpdated = $this->prepareSync('update', $sync, $classified->getToUpdate());

        $preparedDeleted = $this->prepareSync('delete', $sync, $classified->getToDelete());

        $merged = $preparedCreated->merge($preparedUpdated)->merge($preparedDeleted);

        Bus::batch($merged->toArray())
            ->dispatch();

        return $merged;
    }

    private function prepareSync(string $action, Sync $sync, Collection $parsedData): Collection {
        $jobsToDispatch = collect([]);
        foreach ($parsedData as $item) {
            $jobsToDispatch->push(new SyncProcess($item, $sync->uniqueJobId($item), $action, $this));
        }
        return $jobsToDispatch;
    }

    public abstract function createSync(mixed $item): void;
    public abstract function updateSync(mixed $item): void;
    public abstract function deleteSync(mixed $item): void;
}
