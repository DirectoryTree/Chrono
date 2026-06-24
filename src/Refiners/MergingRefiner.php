<?php

namespace Chrono\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

abstract class MergingRefiner implements Refiner
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

        $merged = [];
        $current = $results[0];

        for ($i = 1; $i < count($results); $i++) {
            $next = $results[$i];
            $textBetween = $this->textBetween($text, $current, $next);

            if (! $this->shouldMergeResults($textBetween, $current, $next, $text, $reference, $options)) {
                $merged[] = $current;
                $current = $next;

                continue;
            }

            $current = $this->mergeResults($textBetween, $current, $next, $text, $reference, $options);
        }

        $merged[] = $current;

        return $merged;
    }

    abstract protected function shouldMergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): bool;

    abstract protected function mergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): ParsedResult;

    /**
     * Get the text between two parsed results.
     */
    protected function textBetween(string $text, ParsedResult $current, ParsedResult $next): string
    {
        $afterCurrent = $current->index + strlen($current->text);

        return substr($text, $afterCurrent, $next->index - $afterCurrent);
    }
}
