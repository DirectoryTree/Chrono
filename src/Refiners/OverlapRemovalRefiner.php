<?php

namespace Chrono\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

class OverlapRemovalRefiner implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        if (count($results) < 2) {
            return $results;
        }

        $accepted = [];
        $previous = $results[0];

        for ($i = 1; $i < count($results); $i++) {
            $result = $results[$i];

            if ($result->index >= $previous->index + strlen($previous->text)) {
                $accepted[] = $previous;
                $previous = $result;

                continue;
            }

            if (strlen($result->text) > strlen($previous->text)) {
                $previous = $result;
            }
        }

        $accepted[] = $previous;

        return $accepted;
    }
}
