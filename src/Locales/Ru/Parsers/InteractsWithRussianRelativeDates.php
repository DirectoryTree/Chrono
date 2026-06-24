<?php

namespace Chrono\Locales\Ru\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Ru\RuConstants;
use Chrono\Pattern;
use Chrono\ParsedComponents;

trait InteractsWithRussianRelativeDates
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
            $date = $date->add($unit, (int) round($amount * $direction));
        }

        return $date;
    }

    /**
     * Parse a Russian duration into normalized unit amounts.
     *
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        preg_match_all('/(?<number>'.RuConstants::numberPattern().')\s{0,5}(?<unit>'.RuConstants::timeUnitPattern().')\s{0,5}/iu', $duration, $matches, PREG_SET_ORDER);
        $units = [];

        foreach ($matches as $match) {
            $unit = RuConstants::TIME_UNITS[mb_strtolower($match['unit'])] ?? null;

            if ($unit !== null) {
                $units[$unit] = ($units[$unit] ?? 0) + RuConstants::number($match['number']);
            }
        }

        return $units;
    }

    /**
     * Build a regex pattern for one or more Russian duration units.
     */
    protected function durationPattern(): string
    {
        $single = '(?:'.RuConstants::numberPattern().')\s{0,5}(?:'.RuConstants::timeUnitPattern().')\s{0,5}';

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:и)|,)?\\s*');
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
