<?php

namespace DirectoryTree\Chrono\Locales\It\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Locales\It\ItConstants;
use DirectoryTree\Chrono\ParsedComponents;

trait InteractsWithItalianRelativeDates
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
        foreach ($duration as $unit => $amount) {
            if ($unit === 'quarter') {
                $unit = 'month';
                $amount *= 3;
            }

            $date = $date->add($unit, (int) round($amount * $direction));
        }

        return $date;
    }

    /**
     * Parse an Italian time-unit phrase into duration fragments.
     *
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        $numberPattern = ItConstants::numberPattern();
        $unitPattern = ItConstants::timeUnitPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,3}(?<unit>{$unitPattern})(?![\\pL])/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = ItConstants::timeUnit($match['unit']);

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + ItConstants::number($match['number']);
        }

        return $units;
    }

    /**
     * Build a regex pattern for one or more Italian time-unit fragments.
     */
    protected function durationPattern(): string
    {
        $numberPattern = ItConstants::numberPattern();
        $unitPattern = ItConstants::timeUnitPattern();
        $single = "(?:{$numberPattern})\\s{0,3}(?:{$unitPattern})";

        return "{$single}(?:\\s*(?:,?\\s*(?:e|ed)|,)?\\s*{$single})*";
    }

    /**
     * @param  array<string, int|float>  $duration
     */
    protected function mostSpecificUnit(array $duration): string
    {
        foreach (['second', 'minute', 'hour', 'day', 'week', 'month', 'quarter', 'year'] as $unit) {
            if (array_key_exists($unit, $duration)) {
                return $unit;
            }
        }

        return 'year';
    }
}
