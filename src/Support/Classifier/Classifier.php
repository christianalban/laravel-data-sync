<?php

namespace Alban\LaravelDataSync\Support\Classifier;

use Illuminate\Support\Collection;

abstract class Classifier
{
    public function toMergeProps(): array
    {
        return [];
    }

    public function dataToCompare(): array
    {
        return [];
    }

    public function afterClassifyCreate(Collection $data): Collection
    {
        return $data;
    }

    public function afterClassifyUpdate(Collection $data): Collection
    {
        return $data;
    }

    public function afterClassifyDelete(Collection $data): Collection
    {
        return $data;
    }

    public function classify(Collection $data): Classified {
        $toCreate = collect();
        $toUpdate = collect();
        $toDelete = collect();

        $dataToCompare = $this->dataToCompare();

        foreach ($data as $item) {
            if ($this->compareForCreate($item, $dataToCompare) === null) {
                $toCreate->push($item);
                continue;
            }

            $updatedItem = $this->compareForUpdate($item, $dataToCompare);
            if ($updatedItem !== null) {
                $item = $this->getItemMergedProps($item, $updatedItem);
                $toUpdate->push($item);
                continue;
            }
        }

        foreach ($dataToCompare as $item) {
            $deleteItem = $this->compareForCreate($item, $data->toArray());
            if ($deleteItem === null) {
                $toDelete->push($item);
            }
        }

        $toCreate = $this->afterClassifyCreate($toCreate);
        $toUpdate = $this->afterClassifyUpdate($toUpdate);
        $toDelete = $this->afterClassifyDelete($toDelete);

        return new Classified($toCreate, $toUpdate, $toDelete);
    }

    private function getItemMergedProps(array $item, array $itemExtract): array
    {
        foreach ($this->toMergeProps() as $prop) {
            $item[$prop] = $itemExtract[$prop];
        }

        return $item;
    }

    public abstract function compareForCreate(array $item, array $dataToCompare): bool;
    public abstract function compareForUpdate(array $item, array $dataToCompare): array | null;
}
