<?php

namespace Chrono\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

class ExtractTimezoneRefiner implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        $results = (new ExtractTimezoneOffsetRefiner())->refine($text, $results, $reference, $options);

        return (new ExtractTimezoneAbbrRefiner())->refine($text, $results, $reference, $options);
    }
}
