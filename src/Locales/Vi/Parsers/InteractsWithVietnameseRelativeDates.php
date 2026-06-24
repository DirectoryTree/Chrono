<?php

namespace Chrono\Locales\Vi\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Vi\ViConstants;
use Chrono\Pattern;
use Chrono\ParsedComponents;

trait InteractsWithVietnameseRelativeDates
{
    /**
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
            $date = $date->add($unit, (int) round($amount * $direction));
        }

        return $date;
    }

    /**
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        $numberPattern = ViConstants::numberPattern();
        $unitPattern = ViConstants::timeUnitPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,5}(?<unit>{$unitPattern})\\s{0,5}/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = ViConstants::TIME_UNITS[mb_strtolower($match['unit'])] ?? null;

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + ViConstants::number($match['number']);
        }

        return $units;
    }

    /**
     * Get the parser pattern.
     */
    protected function durationPattern(): string
    {
        $single = '(?:'.ViConstants::numberPattern().')\s{0,5}(?:'.ViConstants::timeUnitPattern().')\s{0,5}';

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:và)|,)?\\s*');
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
