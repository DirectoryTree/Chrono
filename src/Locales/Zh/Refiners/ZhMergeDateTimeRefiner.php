<?php

namespace Chrono\Locales\Zh\Refiners;

use Chrono\Calculation\MergingCalculation;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

readonly class ZhMergeDateTimeRefiner extends MergingRefiner
{
    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): bool
    {
        return ($date->start->isCertain('day') || $date->start->isCertain('weekday'))
            && ! $date->start->isCertain('hour')
            && ! $time->start->isCertain('day')
            && $time->start->isCertain('hour')
            && preg_match('/^\s*$/u', $textBetween) === 1;
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): ParsedResult
    {
        $date = MergingCalculation::mergeDateTimeResult($date, $time);

        $datePosition = strpos($text, $date->text, $date->index);
        $dateEnd = $datePosition === false ? null : $datePosition + strlen($date->text);
        $timePosition = $dateEnd === null ? false : strpos($text, $time->text, $dateEnd);
        $separator = $dateEnd !== null && $timePosition !== false
            ? substr($text, $dateEnd, $timePosition - $dateEnd)
            : $textBetween;

        $date->text = $date->text.$separator.$time->text;
        $date->addTag('refiner/mergeDateFollowedByTime');

        return $date;
    }
}
