<?php

namespace DirectoryTree\Chrono\Locales\En\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiners\MergingRefiner;

readonly class EnMergeRelativeFollowByDateRefiner extends MergingRefiner
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
            ->assign('month', $resultDate->month);

        if (str_contains($relative->text, 'month') || str_contains($relative->text, 'year')) {
            $relative->start
                ->delete('day')
                ->imply('day', $resultDate->day);
        } else {
            $relative->start->assign('day', $resultDate->day);
        }

        $relative->start
            ->assign('hour', $resultDate->hour)
            ->assign('minute', $resultDate->minute)
            ->assign('second', $resultDate->second);

        if ($relative->start->get('weekday') !== null) {
            $weekdayWasCertain = $relative->start->isCertain('weekday');

            $relative->start->delete('weekday');

            $weekdayWasCertain
                ? $relative->start->assign('weekday', $resultDate->dayOfWeek)
                : $relative->start->imply('weekday', $resultDate->dayOfWeek);
        }

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
