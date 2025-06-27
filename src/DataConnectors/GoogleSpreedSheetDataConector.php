<?php

namespace Alban\LaravelDataSync\DataConnectors;

use Revolution\Google\Sheets\Facades\Sheets;

class GoogleSpreedSheetDataConector implements DataConector
{
    public function __construct(
        private string $spreadsheetId,
        private string|array $sheetName,
        private int $startRow,
    ) {}

    public function getData(): array
    {
        if (is_array($this->sheetName)) {
            $data = [];
            foreach ($this->sheetName as $sheetName) {
                $data = array_merge($data, $this->getSingleSheetData($sheetName));
            }

            return $data;
        }

        return $this->getSingleSheetData($this->sheetName);
    }

    private function getSingleSheetData(string $sheetName): array
    {
        $rows = Sheets::spreadsheet($this->spreadsheetId)->sheet($sheetName)->get();
        $header = $rows->get($this->startRow);
        $heads = [];
        foreach ($header as $head) {
            $heads[] = strtolower(str_replace(' ', '', $head));
        }

        $rows = $rows->slice($this->startRow + 1)
            ->map(function ($row) use ($heads) {
                $data = [];
                foreach ($heads as $key => $head) {
                    if (!isset($row[$key])) {
                        $data[] = '';
                        continue;
                    }
                    $data[] = $row[$key];
                }

                return $data;
            });

        $values = Sheets::collection(header: $heads, rows: $rows);
        return $values->toArray();
    }
}
