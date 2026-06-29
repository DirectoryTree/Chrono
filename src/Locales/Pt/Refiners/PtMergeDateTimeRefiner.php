<?php

namespace Chrono\Locales\Pt\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

readonly class PtMergeDateTimeRefiner extends MergingRefiner
{
    /**
     * Determine if a date-only result should merge with a following time-only result.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): bool
    {
        if ($this->isCasualTimeReference($date) && $time->start->isOnlyTime()) {
            return preg_match('/^\s*(?:,|à|às?)?\s*$/iu', $textBetween) === 1;
        }

        if ((! $date->start->isCertain('day') && ! $date->start->isCertain('weekday')) || $date->start->isCertain('hour')) {
            return false;
        }

        if ($time->start->isCertain('day') || ! $time->start->isCertain('hour')) {
            return false;
        }

        return preg_match('/^\s*(?:,|à)?\s*$/iu', $textBetween) === 1;
    }

    /**
     * Merge a date-only result with its following time-only result.
     */
    protected function mergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): ParsedResult
    {
        $hour = $this->hour($date, $time);

        $date->start
            ->assign('hour', $hour)
            ->assign('minute', $time->start->date()->minute)
            ->assign('second', $time->start->date()->second);

        if ($time->end !== null) {
            $time->end
                ->assign('year', $date->start->date()->year)
                ->assign('month', $date->start->date()->month)
                ->assign('day', $date->start->date()->day);

            $date = new ParsedResult($date->index, $date->text, $date->start, $time->end, $date->tags());
        }

        if (($timeOffset = $time->start->timezoneOffset()) !== null) {
            $date->start->assign('timezoneOffset', $timeOffset);
        }

        $date->text = substr($text, $date->index, $time->index + strlen($time->text) - $date->index);
        $date->addTag('refiner/mergeDateFollowedByTime');

        return $date;
    }

    /**
     * Determine whether the result is a casual time reference.
     */
    protected function isCasualTimeReference(ParsedResult $result): bool
    {
        return in_array('parser/PTCasualTimeParser', $result->tags(), true)
            && array_intersect($result->tags(), [
                'casualReference/morning',
                'casualReference/afternoon',
                'casualReference/evening',
            ]) !== [];
    }

    /**
     * Resolve the hour value.
     */
    protected function hour(ParsedResult $date, ParsedResult $time): int
    {
        $hour = $time->start->date()->hour;

        if (! $this->isCasualTimeReference($date) || $hour >= 12) {
            return $hour;
        }

        if (array_intersect($date->tags(), ['casualReference/afternoon', 'casualReference/evening']) !== []) {
            return $hour + 12;
        }

        return $hour;
    }
}
