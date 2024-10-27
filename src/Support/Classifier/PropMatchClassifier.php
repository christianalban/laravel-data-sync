<?php

namespace Alban\LaravelDataSync\Support\Classifier;

use Illuminate\Support\Str;

class PropMatchClassifier extends Classifier
{
    public function __construct(
        private string $key
    ) {}

    public function compareForCreate(array $item, array $dataToCompare): bool
    {
        $dataToCompare = array_values($dataToCompare);

        return array_search(trim($item[$this->key]), array_column($dataToCompare, $this->key)) === false;
    }

    public function compareForUpdate(array $item, array $dataToCompare): array | null
    {
        $dataToCompare = array_values($dataToCompare);

        $searchedKey = array_search(trim($item[$this->key]), array_column($dataToCompare, $this->key));

        $itemComparison = $dataToCompare[$searchedKey];

        foreach ($item as $key => $value) {
            if (array_key_exists($key, $itemComparison) && !$this->compareContent($value, $itemComparison[$key])) {
                return $itemComparison;
            }
        }

        return null;
    }

    private function compareContent(mixed $value, mixed $valueToCompare): bool
    {
        if ((is_array($value) || is_object($value)) && (is_array($valueToCompare) || is_object($valueToCompare))) {
            if (is_object($value)) {
                $value = (array) $value;
            }

            if (is_object($valueToCompare)) {
                $valueToCompare = (array) $valueToCompare;
            }

            return $this->compareArrayContent($value, $valueToCompare);
        }

        return $this->compareSingleContent($value, $valueToCompare);
    }

    private function compareArrayContent(array $value, array $valueToCompare): bool
    {
        foreach ($value as $key => $item) {
            if (!array_key_exists($key, $valueToCompare)) {
                return false;
            }

            if (!$this->compareContent($item, $valueToCompare[$key])) {
                return false;
            }
        }

        return true;
    }

    private function compareSingleContent($value, string $valueToCompare): bool
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                if (!$this->compareContent($item, $valueToCompare)) {
                    return false;
                }
            }
        }
        $cleanedValue = $value;
        $cleanedValueToCompare = $valueToCompare;

        if (is_bool($value)) {
            $cleanedValueToCompare = $valueToCompare == '1' ? true : false;
        }

        if (is_int($value)) {
            $cleanedValueToCompare = (int) $valueToCompare;
        }

        if (is_float($value)) {
            $cleanedValueToCompare = (float) $valueToCompare;
        }

        if (is_string($value)) {
            $cleanedValue = $this->removeSpaces($value);
            $cleanedValueToCompare = $this->removeSpaces($valueToCompare);
        }

        return $cleanedValue == $cleanedValueToCompare;
    }

    private function removeSpaces(string $value): string
    {
        return Str::of($value)->trim()->replace(' ', '')->replace("\n", '')->replace("\r", '')->stripTags()->value();
    }
}
