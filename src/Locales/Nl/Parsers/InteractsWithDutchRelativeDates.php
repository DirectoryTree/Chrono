<?php

namespace Chrono\Locales\Nl\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Dates;
use Chrono\Locales\Nl\NlConstants;
use Chrono\ParsedComponents;
use Chrono\Pattern;

trait InteractsWithDutchRelativeDates
{
    /**
     * @param  array<string, int>  $known
     */
    protected function relativeComponents(CarbonImmutable $date, array $known): ParsedComponents
    {
        $components = new ParsedComponents($date, []);
        Dates::implySimilarTime($components, $date);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        return $components;
    }

    /**
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
     * @param  array<string, int|float>  $duration
     */
    protected function applyDuration(CarbonImmutable $date, array $duration, int $direction): CarbonImmutable
    {
        $duration = array_map(
            fn (int|float $amount): int|float => $amount * $direction,
            $duration,
        );

        foreach ([
            'year' => ['month', 12],
            'month' => ['week', 4],
            'week' => ['day', 7],
            'day' => ['hour', 24],
            'hour' => ['minute', 60],
            'minute' => ['second', 60],
            'second' => ['millisecond', 1000],
            'millisecond' => [null, 0],
        ] as $unit => [$smallerUnit, $ratio]) {
            if (! array_key_exists($unit, $duration)) {
                continue;
            }

            $amount = (float) $duration[$unit];
            $whole = (int) floor($amount);

            $date = $date->add($unit, $whole);

            if ($smallerUnit !== null && ($remaining = $amount - $whole) > 0) {
                $duration[$smallerUnit] = ($duration[$smallerUnit] ?? 0) + round($remaining * $ratio);
            }
        }

        return $date;
    }

    /**
     * Parse a Dutch time-unit phrase into duration fragments.
     *
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        $numberPattern = NlConstants::numberPattern();
        $unitPattern = NlConstants::timeUnitPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,5}(?<unit>{$unitPattern})\\s{0,5}/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = NlConstants::timeUnit($match['unit']);

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + NlConstants::number($match['number']);
        }

        return $units;
    }

    /**
     * Build a regex pattern for one or more Dutch time-unit fragments.
     */
    protected function durationPattern(): string
    {
        $numberPattern = NlConstants::numberPattern();
        $unitPattern = NlConstants::timeUnitPattern();
        $single = "(?:{$numberPattern})\\s{0,5}(?:{$unitPattern})\\s{0,5}";

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:en)|,)?\\s*');
    }

    /**
     * @param  array<string, int|float>  $duration
     */
    protected function mostSpecificUnit(array $duration): string
    {
        foreach (['second', 'minute', 'hour', 'day', 'week', 'month', 'year'] as $unit) {
            if (array_key_exists($unit, $duration)) {
                return $unit;
            }
        }

        return 'year';
    }
}
