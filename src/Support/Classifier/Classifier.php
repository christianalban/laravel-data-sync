<?php

namespace Alban\LaravelDataSync\Support\Classifier;

use Illuminate\Support\Collection;

abstract class Classifier
{
    protected array $toMergeProps;

    public function classify(Collection $data, array $dataToCompare): Classified {
        $toCreate = collect();
        $toUpdate = collect();
        $toDelete = collect();

        foreach ($data as $item) {
            if ($this->compare($item, $dataToCompare) === null) {
                $toCreate->push($item);
            }

            $updatedItem = $this->compare($item, $dataToCompare);
            if ($updatedItem !== null) {
                $item = $this->getItemMergedProps($item, $updatedItem);
                $toUpdate->push($item);
            }
        }

        foreach ($dataToCompare as $item) {
            $deleteItem = $this->compare($item, $data->toArray());
            if ($deleteItem === null) {
                $toDelete->push($item);
            }
        }

        return new Classified($toCreate, $toUpdate, $toDelete);
    }

    public function mergeProps(array $props): self
    {
        $this->toMergeProps = $props;

        return $this;
    }

    private function getItemMergedProps(array $item, array $itemExtract): array
    {
        foreach ($this->toMergeProps as $prop) {
            $item[$prop] = $itemExtract[$prop];
        }

        return $item;
    }

    public abstract function compare(array $item, array $dataToCompare): array | null;
}
