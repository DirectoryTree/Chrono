<?php

namespace DirectoryTree\Chrono\Locales\Fr\Refiners;

use DirectoryTree\Chrono\Calculation\MergingCalculation;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiners\MergingRefiner;

readonly class FrMergeDateTimeRefiner extends MergingRefiner
{
    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): bool
    {
        if ((! $current->start->isCertain('day') && ! $current->start->isCertain('weekday')) || $current->start->isCertain('hour')) {
            return false;
        }

        if ($next->start->isCertain('day')) {
            return false;
        }

        return preg_match('/^\s*(?:T|à|a|au|vers|de|,|-)?\s*$/iu', $textBetween) === 1;
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): ParsedResult
    {
        $date = MergingCalculation::mergeDateTimeResult($date, $time);

        $date->text = substr($text, $date->index, $time->index + strlen($time->text) - $date->index);
        $date->addTag('refiner/mergeDateFollowedByTime');

        return $date;
    }
}
