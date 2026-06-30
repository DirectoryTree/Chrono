<?php

namespace DirectoryTree\Chrono;

use Carbon\CarbonImmutable;

readonly class Dates
{
    /**
     * Assign date components from a matching Carbon date.
     */
    public static function assignSimilarDate(ParsedComponents $components, CarbonImmutable $date): void
    {
        $components
            ->assign('day', $date->day)
            ->assign('month', $date->month)
            ->assign('year', $date->year);
    }

    /**
     * Assign time components from a matching Carbon date.
     */
    public static function assignSimilarTime(ParsedComponents $components, CarbonImmutable $date): void
    {
        $components
            ->assign('hour', $date->hour)
            ->assign('minute', $date->minute)
            ->assign('second', $date->second)
            ->assign('millisecond', $date->millisecond)
            ->assign('meridiem', $date->hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);
    }

    /**
     * Imply date components from a matching Carbon date.
     */
    public static function implySimilarDate(ParsedComponents $components, CarbonImmutable $date): void
    {
        $components
            ->imply('day', $date->day)
            ->imply('month', $date->month)
            ->imply('year', $date->year);
    }

    /**
     * Imply time components from a matching Carbon date.
     */
    public static function implySimilarTime(ParsedComponents $components, CarbonImmutable $date): void
    {
        $components
            ->imply('hour', $date->hour)
            ->imply('minute', $date->minute)
            ->imply('second', $date->second)
            ->imply('millisecond', $date->millisecond)
            ->imply('meridiem', $date->hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);
    }
}
