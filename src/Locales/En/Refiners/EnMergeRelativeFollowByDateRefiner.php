<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

class EnMergeRelativeFollowByDateRefiner extends MergingRefiner
{
    use InteractsWithEnglishRefiners;

    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $relative, ParsedResult $date, string $text, Reference $reference, Options $options): bool
    {
        if (preg_match('/^\s*$/', $textBetween) !== 1) {
            return false;
        }

        return in_array('result/relativeDate', $relative->tags(), true)
            && $this->hasReferenceDirection($relative)
            && $this->isReferenceDate($date)
            && $this->relativeDuration($relative->text) !== [];
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $relative, ParsedResult $date, string $text, Reference $reference, Options $options): ParsedResult
    {
        $duration = $this->relativeDuration($relative->text);
        $direction = $this->hasEarlierReferenceDate($relative) ? -1 : 1;
        $resultDate = $this->applyDuration($date->start->date(), $duration, $direction);

        $relative->start
            ->assign('year', $resultDate->year)
            ->assign('month', $resultDate->month)
            ->assign('day', $resultDate->day)
            ->assign('hour', $resultDate->hour)
            ->assign('minute', $resultDate->minute)
            ->assign('second', $resultDate->second);

        $relative->text .= $textBetween.$date->text;
        $relative->addTag('refiner/mergeRelativeFollowByDate');

        return $relative;
    }

    /**
     * Determine whether the result has a reference direction.
     */
    protected function hasReferenceDirection(ParsedResult $result): bool
    {
        return $this->hasEarlierReferenceDate($result) || $this->hasLaterReferenceDate($result);
    }

    /**
     * Determine whether the result has an earlier reference date.
     */
    protected function hasEarlierReferenceDate(ParsedResult $result): bool
    {
        return preg_match('/\s+(?:before|from)$/i', $result->text) === 1;
    }

    /**
     * Determine whether the result has a later reference date.
     */
    protected function hasLaterReferenceDate(ParsedResult $result): bool
    {
        return preg_match('/\s+(?:after|since)$/i', $result->text) === 1;
    }

    /**
     * Determine whether the result is a reference date.
     */
    protected function isReferenceDate(ParsedResult $result): bool
    {
        return $result->start->isCertain('day') || $result->start->isCertain('weekday');
    }
}
