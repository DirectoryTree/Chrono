<?php

namespace Chrono;

class CasualReferences
{
    /**
     * Create components for the current reference moment.
     */
    public static function now(Reference $reference): ParsedComponents
    {
        $date = $reference->date;
        $components = new ParsedComponents($date, []);

        Dates::assignSimilarDate($components, $date);
        Dates::assignSimilarTime($components, $date);
        $components->assign('timezoneOffset', $date->offsetMinutes);

        return $components->addTag('casualReference/now');
    }

    /**
     * Create components for today.
     */
    public static function today(Reference $reference): ParsedComponents
    {
        $date = $reference->date;
        $components = new ParsedComponents($date, []);

        Dates::assignSimilarDate($components, $date);
        Dates::implySimilarTime($components, $date);
        $components->delete('meridiem');

        return $components->addTag('casualReference/today');
    }

    /**
     * Create components for yesterday.
     */
    public static function yesterday(Reference $reference): ParsedComponents
    {
        return self::theDayBefore($reference, 1)->addTag('casualReference/yesterday');
    }

    /**
     * Create components for tomorrow.
     */
    public static function tomorrow(Reference $reference): ParsedComponents
    {
        return self::theDayAfter($reference, 1)->addTag('casualReference/tomorrow');
    }

    /**
     * Create components for a number of days before the reference.
     */
    public static function theDayBefore(Reference $reference, int $days): ParsedComponents
    {
        return self::theDayAfter($reference, -$days);
    }

    /**
     * Create components for a number of days after the reference.
     */
    public static function theDayAfter(Reference $reference, int $days): ParsedComponents
    {
        $date = $reference->date->addDays($days);
        $components = new ParsedComponents($reference->date, []);

        Dates::assignSimilarDate($components, $date);
        Dates::implySimilarTime($components, $date);
        $components->delete('meridiem');

        return $components;
    }

    /**
     * Create components for tonight.
     */
    public static function tonight(Reference $reference, int $implyHour = 22): ParsedComponents
    {
        $date = $reference->date;
        $components = new ParsedComponents($date, []);

        Dates::assignSimilarDate($components, $date);
        $components
            ->imply('hour', $implyHour)
            ->imply('meridiem', Meridiem::PM->value);

        return $components->addTag('casualReference/tonight');
    }

    /**
     * Create components for last night.
     */
    public static function lastNight(Reference $reference, int $implyHour = 0): ParsedComponents
    {
        $date = $reference->date;
        $components = new ParsedComponents($date, []);

        if ($date->hour < 6) {
            $date = $date->subDay();
        }

        Dates::assignSimilarDate($components, $date);
        $components->imply('hour', $implyHour);

        return $components;
    }

    /**
     * Create components for evening.
     */
    public static function evening(Reference $reference, int $implyHour = 20): ParsedComponents
    {
        return (new ParsedComponents($reference->date, []))
            ->imply('meridiem', Meridiem::PM->value)
            ->imply('hour', $implyHour)
            ->addTag('casualReference/evening');
    }

    /**
     * Create components for yesterday evening.
     */
    public static function yesterdayEvening(Reference $reference, int $implyHour = 20): ParsedComponents
    {
        $date = $reference->date->subDay();
        $components = new ParsedComponents($reference->date, []);

        Dates::assignSimilarDate($components, $date);
        $components
            ->imply('hour', $implyHour)
            ->imply('meridiem', Meridiem::PM->value);

        return $components
            ->addTag('casualReference/yesterday')
            ->addTag('casualReference/evening');
    }

    /**
     * Create components for midnight.
     */
    public static function midnight(Reference $reference): ParsedComponents
    {
        $components = new ParsedComponents($reference->date, []);

        if ($reference->date->hour > 2) {
            $components->addDurationAsImplied(['day' => 1]);
        }

        return $components
            ->assign('hour', 0)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('casualReference/midnight');
    }

    /**
     * Create components for morning.
     */
    public static function morning(Reference $reference, int $implyHour = 6): ParsedComponents
    {
        return (new ParsedComponents($reference->date, []))
            ->imply('meridiem', Meridiem::AM->value)
            ->imply('hour', $implyHour)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('casualReference/morning');
    }

    /**
     * Create components for afternoon.
     */
    public static function afternoon(Reference $reference, int $implyHour = 15): ParsedComponents
    {
        return (new ParsedComponents($reference->date, []))
            ->imply('meridiem', Meridiem::PM->value)
            ->imply('hour', $implyHour)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('casualReference/afternoon');
    }

    /**
     * Create components for noon.
     */
    public static function noon(Reference $reference): ParsedComponents
    {
        return (new ParsedComponents($reference->date, []))
            ->imply('meridiem', Meridiem::AM->value)
            ->assign('hour', 12)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('casualReference/noon');
    }

}
