<?php

namespace Chrono\Locales\Uk\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Uk\UkConstants;
use Chrono\ParsedComponents;
use Chrono\Pattern;

trait InteractsWithUkrainianRelativeDates
{
    /**
     * Build known parsed components for a relative date.
     *
     * @param  array<string, int>  $known
     */
    protected function relativeComponents(CarbonImmutable $date, array $known): ParsedComponents
    {
        $components = new ParsedComponents($date, []);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        return $components;
    }

    /**
     * Return the components that are certain for the most specific unit.
     *
     * @return array<string, int>
     */
    protected function certainComponents(CarbonImmutable $date, string $unit): array
    {
        return match ($unit) {
            'second' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, 'hour' => $date->hour, 'minute' => $date->minute, 'second' => $date->second],
            'minute' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, 'hour' => $date->hour, 'minute' => $date->minute],
            'hour' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, 'hour' => $date->hour],
            'day', 'week' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day],
            'month' => ['year' => $date->year, 'month' => $date->month],
            'year' => ['year' => $date->year],
            default => [],
        };
    }

    /**
     * Apply a parsed duration to the date in the given direction.
     *
     * @param  array<string, int|float>  $duration
     */
    protected function applyDuration(CarbonImmutable $date, array $duration, int $direction): CarbonImmutable
    {
        foreach ($duration as $unit => $amount) {
            $date = $date->add($unit, $amount * $direction);
        }

        return $date;
    }

    /**
     * Parse a Ukrainian duration into normalized unit amounts.
     *
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        preg_match_all('/(?:(?:близько|приблизно)\s{0,3})?(?<number>'.UkConstants::numberPattern().')\s{0,3}(?<unit>'.UkConstants::timeUnitPattern().')\s{0,3}/iu', $duration, $matches, PREG_SET_ORDER);
        $units = [];

        foreach ($matches as $match) {
            $unit = UkConstants::TIME_UNITS[mb_strtolower($match['unit'])] ?? null;

            if ($unit !== null) {
                $units[$unit] = ($units[$unit] ?? 0) + UkConstants::number($match['number']);
            }
        }

        return $units;
    }

    /**
     * Build a regex pattern for one or more Ukrainian duration units.
     */
    protected function durationPattern(): string
    {
        $single = '(?:(?:близько|приблизно)\s{0,3})?(?:'.UkConstants::numberPattern().')\s{0,3}(?:'.UkConstants::timeUnitPattern().')\s{0,3}';

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:і)|,)?\\s*');
    }

    /**
     * Return the finest-grained unit present in a parsed duration.
     *
     * @param  array<string, int|float>  $duration
     */
    protected function mostSpecificUnit(array $duration): string
    {
        foreach (['second', 'minute', 'hour', 'day', 'week', 'month', 'quarter', 'year'] as $unit) {
            if (array_key_exists($unit, $duration)) {
                return $unit === 'quarter' ? 'month' : $unit;
            }
        }

        return 'year';
    }
}
