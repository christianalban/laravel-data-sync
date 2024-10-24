<?php

namespace Alban\LaravelDataSync\Support;

use Alban\LaravelDataSync\DataConnectors\DataConector;
use Alban\LaravelDataSync\DataConnectors\GoogleSpreedSheetDataConector;
use Alban\LaravelDataSync\DataMappers\ColumnMapper;
use Alban\LaravelDataSync\Support\Classifier\Classified;
use Alban\LaravelDataSync\Support\Classifier\Classifier;
use Alban\LaravelDataSync\Support\Parser\Parser;
use Alban\LaravelDataSync\Support\Sync;
use Alban\LaravelDataSync\Support\Synchronizer\Synchronizer;
use Illuminate\Support\Collection;

class DataSync
{
    private Sync $sync;

    public function setSync(Sync $sync): self
    {
        $this->sync = $sync;

        return $this;
    }

    public function prepareForSync(): Collection | Classified
    {
        $data = $this->getData();

        $data = $this->mapData($data);

        $data = $this->applyFilters($data);

        $data = $this->parseData($data);

        return $this->classifyData($data);
    }

    public function startSync(Collection | Classified $data): Collection | Classified
    {
        if ($data instanceof Collection) {
            return $data;
        }

        $synchronizer = $this->sync->synchronizer();

        if ($synchronizer === null) {
            return $data;
        }

        if (!$data instanceof Classified) {
            throw new \Exception('Data must be an instance of ' . Classified::class);
        }

        if ($synchronizer instanceof Synchronizer) {
            $synchronizer->sync($this->sync, $data);
        }

        return $data;
    }

    private function classifyData(Collection $data): Classified | Collection
    {
        $classifier = $this->sync->classifier();
        if ($classifier instanceof Classifier) {
            return $classifier->classify($data);
        }

        return $data;
    }

    private function parseData(Collection $data): Collection
    {
        $parser = $this->sync->parser();

        if ($parser instanceof Parser) {
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

    public static function sync(Sync $sync): Collection | Classified
    {
        $synchronizer = new self;

        $synchronizer->setSync($sync);

        $preparedData = $synchronizer->prepareForSync();

        return $synchronizer->startSync($preparedData);
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
