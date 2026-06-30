<?php

namespace DirectoryTree\Chrono\Locales\En\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiners\MergingRefiner;

readonly class EnMergeSpecificDateIntoTimeRangeRefiner extends MergingRefiner
{
    use InteractsWithEnglishRefiners;

    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $timeRange, ParsedResult $date, string $text, Reference $reference, Options $options): bool
    {
        if ($timeRange->end === null || ! $timeRange->start->isCertain('hour') || ! in_array('refiner/mergeTimeFollowedByDate', $timeRange->tags(), true)) {
            return false;
        }

        if (! $date->start->isCertain('day') || ! $date->start->isCertain('month') || ! $date->start->isCertain('year')) {
            return false;
        }

        return $this->isDateTimeConnector($textBetween);
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $timeRange, ParsedResult $date, string $text, Reference $reference, Options $options): ParsedResult
    {
        $timeRange->start
            ->assign('year', $date->start->date()->year)
            ->assign('month', $date->start->date()->month)
            ->assign('day', $date->start->date()->day);

        $timeRange->end
            ->assign('year', $date->start->date()->year)
            ->assign('month', $date->start->date()->month)
            ->assign('day', $date->start->date()->day);

        $timeRange->text = substr($text, $timeRange->index, $date->index + strlen($date->text) - $timeRange->index);
        $timeRange->addTag('refiner/mergeSpecificDateIntoTimeRange');

        return $timeRange;
    }
}
