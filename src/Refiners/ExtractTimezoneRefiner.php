<?php

namespace DirectoryTree\Chrono\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiner;

readonly class ExtractTimezoneRefiner implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        $results = (new ExtractTimezoneOffsetRefiner)->refine($text, $results, $reference, $options);

        return (new ExtractTimezoneAbbrRefiner)->refine($text, $results, $reference, $options);
    }
}
