<?php

namespace DirectoryTree\Chrono\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiner;

abstract readonly class Filter implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        return array_values(array_filter(
            $results,
            fn (ParsedResult $result): bool => $this->isValid($text, $result, $reference, $options),
        ));
    }

    abstract protected function isValid(string $text, ParsedResult $result, Reference $reference, Options $options): bool;
}
