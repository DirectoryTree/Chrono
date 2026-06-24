<?php

namespace Chrono\Refiners;

use Chrono\Calculation\MergingCalculation;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;

abstract class AbstractMergeDateTimeRefiner extends MergingRefiner
{
    /**
     * Get the connector pattern allowed between date and time results.
     */
    abstract protected function patternBetween(): string;

    /**
     * Determine if a date-only and time-only result should merge.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): bool
    {
        if (! (($current->start->isOnlyDate() && $next->start->isOnlyTime())
            || ($next->start->isOnlyDate() && $current->start->isOnlyTime()))) {
            return false;
        }

        return preg_match($this->patternBetween(), $textBetween) === 1;
    }

    /**
     * Merge date-only and time-only results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): ParsedResult
    {
        $isDateFollowedByTime = $current->start->isOnlyDate();

        $result = $isDateFollowedByTime
            ? MergingCalculation::mergeDateTimeResult($current, $next)
            : MergingCalculation::mergeDateTimeResult($next, $current);

        $result = new ParsedResult(
            $current->index,
            $current->text.$textBetween.$next->text,
            $result->start,
            $result->end,
            $result->tags(),
        );

        $result->addTag($isDateFollowedByTime ? 'refiner/mergeDateFollowedByTime' : 'refiner/mergeTimeFollowedByDate');

        return $result;
    }
}
