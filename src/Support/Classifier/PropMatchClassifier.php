<?php

namespace Alban\LaravelDataSync\Support\Classifier;

class PropMatchClassifier extends Classifier
{
    public function __construct(
        private string $key
    ) {}

    public function compare(array $item, array $dataToCompare): array | null
    {
        $dataToCompare = array_values($dataToCompare);

        $searchedKey = array_search(trim($item[$this->key]), array_column($dataToCompare, $this->key));

        return $searchedKey !== false ? $dataToCompare[$searchedKey] : null;
    }
}
