<?php

namespace Chrono\Locales\En\Refiners;

use Carbon\CarbonImmutable;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;
use Chrono\Timezone;

readonly class EnMergeDateTimeRefiner extends MergingRefiner
{
    use InteractsWithEnglishRefiners;

    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): bool
    {
        if (! $date->start->isCertain('day') && ! $date->start->isOnlyWeekdayComponent()) {
            return false;
        }

        if ($date->start->isCertain('hour')) {
            return false;
        }

        if (! $this->isTimeResult($time) || $time->start->isCertain('day')) {
            return false;
        }

        return $this->isDateTimeConnector($textBetween);
    }

    /**
     * Determine whether the result is a time result.
     */
    protected function isTimeResult(ParsedResult $result): bool
    {
        return $result->start->isCertain('hour')
            || in_array('parser/ENCasualTimeParser', $result->start->tags(), true);
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $date, ParsedResult $time, string $text, Reference $reference, Options $options): ParsedResult
    {
        $this->mergeTimeComponents($date->start, $time->start);

        if ($time->end !== null) {
            $this->mergeDateComponents($time->end, $date->start);

            if ($time->end->date()->lt($date->start->date())) {
                $this->mergeDateComponents($time->end, $date->start, $time->end->date()->addDay());
            }

            $date = new ParsedResult($date->index, $date->text, $date->start, $time->end, $date->tags());
            $date->addTag('refiner/mergeTrailingTimeRange');
        }

        if (($timeOffset = $time->start->timezoneOffset()) !== null) {
            $date->start->assign('timezoneOffset', $this->timezoneOffset($time, $date, $options) ?? $timeOffset);
        }

        $date->text = substr($text, $date->index, $time->index + strlen($time->text) - $date->index);
        $date->addTag('refiner/mergeDateFollowedByTime');

        return $date;
    }

    /**
     * Resolve the timezone offset.
     */
    protected function timezoneOffset(ParsedResult $time, ParsedResult $date, Options $options): ?int
    {
        if (preg_match('/\b(?<abbr>[A-Z]{2,4})\s*$/', $time->text, $match) !== 1) {
            return null;
        }

        return Timezone::offset($match['abbr'], $date->start->date(), $options);
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeTimeComponents(ParsedComponents $date, ParsedComponents $time): void
    {
        foreach (['hour', 'minute', 'second', 'millisecond'] as $component) {
            $time->isCertain($component)
                ? $date->assign($component, (int) $time->get($component))
                : $date->imply($component, (int) $time->get($component));
        }

        if ($time->isCertain('meridiem')) {
            $date->assign('meridiem', $time->get('meridiem')->value);
        } elseif ($time->get('meridiem') !== null && $date->get('meridiem') === null) {
            $date->imply('meridiem', $time->get('meridiem')->value);
        }

        if ($date->get('meridiem') === Meridiem::PM && $date->get('hour') < 12) {
            $time->isCertain('hour')
                ? $date->assign('hour', (int) $date->get('hour') + 12)
                : $date->imply('hour', (int) $date->get('hour') + 12);
        }

        $date->addTags($time->tags());
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeDateComponents(ParsedComponents $target, ParsedComponents $source, ?CarbonImmutable $date = null): void
    {
        $date ??= $source->date();

        foreach (['year', 'month', 'day'] as $component) {
            $source->isCertain($component)
                ? $target->assign($component, (int) $date->{$component})
                : $target->imply($component, (int) $date->{$component});
        }
    }
}
