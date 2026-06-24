<?php

namespace Chrono\Calculation;

use Carbon\CarbonImmutable;
use Chrono\ParsedComponents;
use Chrono\Reference;
use Chrono\Weekday;

class Weekdays
{
    /**
     * Create parsing components at the requested weekday.
     */
    public static function createParsingComponentsAtWeekday(Reference $reference, Weekday|int $weekday, ?string $modifier = null): ParsedComponents
    {
        $weekday = $weekday instanceof Weekday ? $weekday->value : $weekday;
        $date = $reference->date
            ->hour(12)
            ->minute(0)
            ->second(0)
            ->millisecond(0);

        $components = new ParsedComponents($date);

        $components->addDurationAsImplied([
            'day' => self::getDaysToWeekday($date, $weekday, $modifier),
        ]);

        $components->assign('weekday', $weekday);

        return $components;
    }

    /**
     * Calculate the signed day distance to a target weekday.
     */
    public static function getDaysToWeekday(CarbonImmutable $date, Weekday|int $weekday, ?string $modifier = null): int
    {
        $weekday = $weekday instanceof Weekday ? $weekday->value : $weekday;

        return match ($modifier) {
            'this' => self::getDaysForwardToWeekday($date, $weekday),
            'last' => self::getBackwardDaysToWeekday($date, $weekday),
            'next' => self::getNextDaysToWeekday($date, $weekday),
            default => self::getDaysToWeekdayClosest($date, $weekday),
        };
    }

    /**
     * Calculate the closest signed day distance to a target weekday.
     */
    public static function getDaysToWeekdayClosest(CarbonImmutable $date, Weekday|int $weekday): int
    {
        $weekday = $weekday instanceof Weekday ? $weekday->value : $weekday;
        $backward = self::getBackwardDaysToWeekday($date, $weekday);
        $forward = self::getDaysForwardToWeekday($date, $weekday);

        return $forward < -$backward ? $forward : $backward;
    }

    /**
     * Calculate the forward day distance to a target weekday.
     */
    public static function getDaysForwardToWeekday(CarbonImmutable $date, Weekday|int $weekday): int
    {
        $weekday = $weekday instanceof Weekday ? $weekday->value : $weekday;
        $days = $weekday - $date->dayOfWeek;

        return $days < 0 ? $days + 7 : $days;
    }

    /**
     * Calculate the backward day distance to a target weekday.
     */
    public static function getBackwardDaysToWeekday(CarbonImmutable $date, Weekday|int $weekday): int
    {
        $weekday = $weekday instanceof Weekday ? $weekday->value : $weekday;
        $days = $weekday - $date->dayOfWeek;

        return $days >= 0 ? $days - 7 : $days;
    }

    /**
     * Calculate the next-week day distance to a target weekday.
     */
    protected static function getNextDaysToWeekday(CarbonImmutable $date, int $weekday): int
    {
        $referenceWeekday = $date->dayOfWeek;

        if ($referenceWeekday === Weekday::SUNDAY->value) {
            return $weekday === Weekday::SUNDAY->value ? 7 : $weekday;
        }

        if ($referenceWeekday === Weekday::SATURDAY->value) {
            return match ($weekday) {
                Weekday::SATURDAY->value => 7,
                Weekday::SUNDAY->value => 8,
                default => 1 + $weekday,
            };
        }

        return ($weekday < $referenceWeekday && $weekday !== Weekday::SUNDAY->value)
            ? self::getDaysForwardToWeekday($date, $weekday)
            : self::getDaysForwardToWeekday($date, $weekday) + 7;
    }
}
