<?php

namespace Alban\LaravelDataSync\DataMappers;

use Alban\LaravelDataSync\Support\Pipe\Pipe;
use Alban\LaravelDataSync\Support\WithColumnMappings;
use Illuminate\Support\Collection;

class ColumnMapper
{
    public function __construct(
        private WithColumnMappings $sheet
    ) {}

    public function mapData(array $dataSheet): Collection
    {
        $responseFormat = [];
        foreach ($dataSheet as $item) {
            $responseFormat[] = $this->mapItem($item);
        }

        return collect($responseFormat);
    }

    private function mapItem(array $item): array
    {
        $mappedData = $this->sheet->columnsMap();
        $mappedItem = [];
        foreach ($mappedData as $key => $value) {
            $columnName = $this->extractColumnName($value);
            $pipes = $this->extractPipes($value);

            $value = $item[$this->renameColumn($columnName)];

            $mappedItem[$key] = $this->nullIfEmpty($this->applyPipes($pipes, $value));
        }

        return $mappedItem;
    }

    private function extractColumnName(array|string $column): string
    {
        if (is_string($column)) {
            $column = explode('|', $column);
        }

        return $column[0];
    }

    private function extractPipes(array|string $column): array
    {
        $pipes = $column;
        if (is_string($column)) {
            $pipes = explode('|', $column);
        }
        array_shift($pipes);

        return $pipes;
    }

    private function applyPipes(array $pipes, string $column): mixed
    {
        return array_reduce($pipes, function ($value, $pipe) {
            return $this->applyPipe($pipe, $value);
        }, $column);
    }

    private function nullIfEmpty(mixed $value): mixed
    {
        if ($value === "") {
            return null;
        }

        return $value;
    }

    private function applyPipe(string|Pipe $pipe, string $column): mixed
    {
        if (is_string($pipe)) {
            $pipe = $this->createPipe($pipe);
        }

        return $pipe->transform($column, ...$pipe->getParams());
    }

    private function extractPipeName(string $pipe): string
    {
        return explode(':', $pipe)[0];
    }

    private function extractPipeParams(string $pipe): array
    {
        $params = explode(':', $pipe);
        array_shift($params);

        return $params;
    }

    private function createPipe(string $pipe): Pipe
    {
        $pipeName = $this->extractPipeName($pipe);
        $params = $this->extractPipeParams($pipe);

        $pipes = config('data-sync.pipes');

        return new $pipes[$pipeName](...$params);
    }

    private function renameColumn(string $column): string
    {
        return strtolower(str_replace(' ', '', $column));
    }
}
