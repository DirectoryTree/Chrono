<?php

namespace DirectoryTree\Chrono\Calculation;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;

readonly class MergingCalculation
{
    /**
     * Merge date and time parsing results using upstream Chrono's merge semantics.
     */
    public static function mergeDateTimeResult(ParsedResult $dateResult, ParsedResult $timeResult): ParsedResult
    {
        $start = self::mergeDateTimeComponent($dateResult->start, $timeResult->start);
        $end = null;

        if ($dateResult->end !== null || $timeResult->end !== null) {
            $endDate = $dateResult->end ?? $dateResult->start;
            $endTime = $timeResult->end ?? $timeResult->start;
            $end = self::mergeDateTimeComponent($endDate, $endTime);

            if ($dateResult->end === null && $end->date()->lessThan($start->date())) {
                $nextDay = $end->date()->addDay();

                $end->isCertain('day')
                    ? Dates::assignSimilarDate($end, $nextDay)
                    : Dates::implySimilarDate($end, $nextDay);
            }
        }

        return new ParsedResult($dateResult->index, $dateResult->text, $start, $end, $dateResult->tags());
    }

    /**
     * Merge the time components into a cloned date component set.
     */
    public static function mergeDateTimeComponent(ParsedComponents $dateComponent, ParsedComponents $timeComponent): ParsedComponents
    {
        $dateTimeComponent = $dateComponent->clone();

        if ($timeComponent->isCertain('hour')) {
            $dateTimeComponent->assign('hour', (int) $timeComponent->get('hour'));
            $dateTimeComponent->assign('minute', (int) $timeComponent->get('minute'));

            if ($timeComponent->isCertain('second')) {
                $dateTimeComponent->assign('second', (int) $timeComponent->get('second'));

                $timeComponent->isCertain('millisecond')
                    ? $dateTimeComponent->assign('millisecond', (int) $timeComponent->get('millisecond'))
                    : $dateTimeComponent->imply('millisecond', (int) $timeComponent->get('millisecond'));
            } else {
                $dateTimeComponent->imply('second', (int) $timeComponent->get('second'));
                $dateTimeComponent->imply('millisecond', (int) $timeComponent->get('millisecond'));
            }
        } else {
            $dateTimeComponent
                ->imply('hour', (int) $timeComponent->get('hour'))
                ->imply('minute', (int) $timeComponent->get('minute'))
                ->imply('second', (int) $timeComponent->get('second'))
                ->imply('millisecond', (int) $timeComponent->get('millisecond'));
        }

        if ($timeComponent->isCertain('timezoneOffset')) {
            $dateTimeComponent->assign('timezoneOffset', (int) $timeComponent->get('timezoneOffset'));
        }

        $dateHasMeaningfulMeridiem = $dateComponent->get('meridiem') !== null
            && ($dateComponent->isCertain('meridiem') || self::hasCasualReferenceTag($dateComponent));

        if ($timeComponent->isCertain('meridiem')) {
            $dateTimeComponent->assign('meridiem', $timeComponent->get('meridiem')->value);
        } elseif ($timeComponent->get('meridiem') !== null && ! $dateHasMeaningfulMeridiem) {
            $dateTimeComponent->imply('meridiem', $timeComponent->get('meridiem')->value);
        }

        if ($dateTimeComponent->get('meridiem') === Meridiem::PM && $dateTimeComponent->get('hour') < 12) {
            $timeComponent->isCertain('hour')
                ? $dateTimeComponent->assign('hour', (int) $dateTimeComponent->get('hour') + 12)
                : $dateTimeComponent->imply('hour', (int) $dateTimeComponent->get('hour') + 12);
        }

        return $dateTimeComponent->addTags($dateComponent->tags())->addTags($timeComponent->tags());
    }

    /**
     * Determine if a component set has a casual reference tag.
     */
    protected static function hasCasualReferenceTag(ParsedComponents $components): bool
    {
        foreach ($components->tags() as $tag) {
            if (str_starts_with($tag, 'casualReference/')) {
                return true;
            }
        }

        return false;
    }
}
