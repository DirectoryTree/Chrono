<?php

namespace DirectoryTree\Chrono\Locales\Ja\Refiners;

use DirectoryTree\Chrono\Calculation\MergingCalculation;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiners\MergingRefiner;

readonly class JaMergeDateTimeRefiner extends MergingRefiner
{
    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): bool
    {
        if ((! $date->start->isCertain('day') && ! $date->start->isCertain('weekday')) || $date->start->isCertain('hour')) {
            return false;
        }

        if ($time->start->isCertain('day') || ! $time->start->isCertain('hour')) {
            return false;
        }

        return preg_match('/^\s*(?:の)?\s*$/u', $textBetween) === 1;
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
