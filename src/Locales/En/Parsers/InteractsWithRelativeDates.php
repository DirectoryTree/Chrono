<?php

namespace DirectoryTree\Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\ParsedComponents;

trait InteractsWithRelativeDates
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
            'second' => [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
            ],
            'minute' => [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
                'hour' => $date->hour,
                'minute' => $date->minute,
            ],
            'hour' => [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
                'hour' => $date->hour,
            ],
            'day', 'week' => [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ],
            'month' => [
                'year' => $date->year,
                'month' => $date->month,
            ],
            'year' => [
                'year' => $date->year,
            ],
            default => [],
        };
    }

    /**
     * @param  array<string, int>  $duration
     */
    protected function applyDuration(CarbonImmutable $date, array $duration, int $direction): CarbonImmutable
    {
        foreach ($duration as $unit => $amount) {
            $date = $date->add($unit, $amount * $direction);
        }

        return $date;
    }

    /**
     * Resolve the duration amount.
     */
    protected function amount(string $amount, ?string $unit = null): int
    {
        $amount = strtolower($amount);

        if (str_starts_with($amount, 'half')) {
            return $unit === 'minute' ? 30 : 1;
        }

        return [
            'a' => 1,
            'an' => 1,
            'the' => 1,
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'ten' => 10,
            'few' => 3,
            'a few' => 3,
            'a couple of' => 2,
            'several' => 7,
        ][$amount] ?? (int) $amount;
    }

    /**
     * Resolve the duration unit.
     */
    protected function unit(string $unit): string
    {
        return match (strtolower($unit)) {
            's', 'sec', 'secs', 'second', 'seconds' => 'second',
            'm', 'min', 'mins', 'minute', 'minutes' => 'minute',
            'h', 'hr', 'hrs', 'hour', 'hours' => 'hour',
            'd', 'day', 'days' => 'day',
            'w', 'week', 'weeks' => 'week',
            'mo', 'mon', 'mons', 'month', 'months' => 'month',
            'qtr', 'qtrs', 'quarter', 'quarters' => 'quarter',
            'y', 'yr', 'yrs', 'year', 'years' => 'year',
            default => strtolower(rtrim($unit, 's')),
        };
    }

    /**
     * @return array<string, int>
     */
    protected function duration(string $duration): array
    {
        preg_match_all('/(?:(?<number>\d+(?:\.\d+)?)\s*|(?<word>a\s+few|a\s+couple\s+of|several|an?|the|one|two|three|four|five|six|seven|eight|nine|ten|few|half\s+an?)\s+)(?<unit>seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|weeks?|w|months?|mons?|mos?|mo|quarters?|qtrs?|qtr|years?|yrs?|y)(?![a-z])/i', $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = $this->unit($match['unit']);
            $amountText = ($match['number'] ?? '') !== '' ? $match['number'] : $match['word'];

            if (str_starts_with(strtolower($amountText), 'half') && $unit === 'hour') {
                $units['minute'] = ($units['minute'] ?? 0) + 30;

                continue;
            }

            if (($match['number'] ?? '') !== '' && str_contains($amountText, '.')) {
                $this->addDecimalDuration($units, $unit, (float) $amountText);

                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + $this->amount($amountText, $unit);
        }

        return $units;
    }

    /**
     * @param  array<string, int>  $units
     */
    protected function addDecimalDuration(array &$units, string $unit, float $amount): void
    {
        [$unit, $amount] = match ($unit) {
            'week' => ['day', $amount * 7],
            'day' => ['hour', $amount * 24],
            'hour' => ['minute', $amount * 60],
            'minute' => ['second', $amount * 60],
            default => [$unit, $amount],
        };

        $units[$unit] = ($units[$unit] ?? 0) + (int) round($amount);
    }

    /**
     * @param  array<string, int>  $duration
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

    /**
     * Determine whether the duration contains a strict abbreviated unit.
     */
    protected function hasStrictAbbreviatedUnit(string $duration): bool
    {
        return preg_match('/(?:\d+(?:\.\d+)?|a|an|one|two|three|four|five|six|seven|eight|nine|ten|few)\s*(?:secs?|mins?|hrs?|[smhdw]|mo|mons?|qtrs?|yrs?|y)\b/i', $duration) === 1;
    }
}
