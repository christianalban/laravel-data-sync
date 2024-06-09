<?php

namespace Alban\LaravelDataSync\Support;

use Alban\LaravelDataSync\DataConnectors\DataConector;
use Alban\LaravelDataSync\DataConnectors\GoogleSpreedSheetDataConector;
use Alban\LaravelDataSync\DataMappers\ColumnMapper;
use Alban\LaravelDataSync\Support\Classifier\Classified;
use Alban\LaravelDataSync\Support\Sync;
use Illuminate\Support\Collection;

class DataSync
{
    private Sync $sync;

    public function setSync(Sync $sync): self
    {
        $this->sync = $sync;

        return $this;
    }

    public function startSync(): void
    {
        $data = $this->getData();

        $data = $this->mapData($data);

        $data = $this->applyFilters($data);

        $data = $this->parseData($data);

        $resultClassified = $this->classifyData($data);

        if ($resultClassified instanceof Classified) {
            $resultClassified = $resultClassified->getToUpdate()->first();
        }

        dd($resultClassified);
    }

    private function classifyData(Collection $data): Classified | Collection
    {
        if ($this->sync->classifier()) {
            $classifier = $this->sync->classifier();

            return $classifier->classify($data, $this->sync->getCompareData());
        }

        return $data;
    }

    private function parseData(Collection $data): Collection
    {
        if ($this->sync->parser()) {
            $parser = $this->sync->parser();

            return $parser->parse($data);
        }

        return $data;
    }

    private function applyFilters(Collection $data): Collection
    {
        if ($this->sync instanceof MustApplyFilter) {
            return $data->filter(function ($item) {
                return $this->sync->filter($item);
            });
        }
    }

    public static function sync(Sync $sync): void
    {
        $synchronizer = new self;

        $synchronizer->setSync($sync);

        $synchronizer->startSync();
    }

    private function getData(): array
    {
        $dataConector = $this->makeDataConector();

        return $dataConector->getData();
    }

    private function mapData(array $data): Collection
    {
        if ($this->sync instanceof WithColumnMappings) {
            $mapper = new ColumnMapper($this->sync);
            return $mapper->mapData($data);
        }

        return $data;
    }

    private function makeDataConector(): DataConector
    {
        if ($this->sync instanceof FromGoogleSheet) {
            return $this->syncFromGoogleSheet();
        }

        throw new \Exception('Data connector not found');
    }

    private function syncFromGoogleSheet(): DataConector
    {
        $sheetId = $this->sync->getSheetId();
        $sheetName = $this->sync->getSheetName();

        return new GoogleSpreedSheetDataConector($sheetId, $sheetName);
    }
}
