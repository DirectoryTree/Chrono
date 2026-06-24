<?php

namespace Chrono\Calculation;

use Carbon\CarbonImmutable;

class Duration
{
    public const EMPTY = [
        'day' => 0,
        'second' => 0,
        'millisecond' => 0,
    ];

    /**
     * Add a duration to a date using upstream Chrono's duration semantics.
     *
     * @param  array<string, int|float>  $duration
     */
    public static function add(CarbonImmutable $date, array $duration): CarbonImmutable
    {
        return self::addWithNormalizedDuration($date, $duration)[0];
    }

    /**
     * Return a duration with every amount reversed.
     *
     * @param  array<string, int|float>  $duration
     * @return array<string, int|float>
     */
    public static function reverse(array $duration): array
    {
        return array_map(fn (int|float $amount): int|float => -$amount, $duration);
    }

    /**
     * Add a duration and return the normalized duration used during calculation.
     *
     * @param  array<string, int|float>  $duration
     * @return array{0: CarbonImmutable, 1: array<string, int|float>}
     */
    public static function addWithNormalizedDuration(CarbonImmutable $date, array $duration): array
    {
        $duration = self::normalizeUnits($duration);

        if (array_key_exists('year', $duration)) {
            $years = (float) $duration['year'];
            $floor = (int) floor($years);
            $date = $date->addYears($floor);
            $remaining = $years - $floor;

            if ($remaining > 0) {
                $duration['month'] = ($duration['month'] ?? 0) + ($remaining * 12);
            }
        }

        if (array_key_exists('quarter', $duration)) {
            $date = $date->addMonths((int) floor((float) $duration['quarter']) * 3);
        }

        if (array_key_exists('month', $duration)) {
            $months = (float) $duration['month'];
            $floor = (int) floor($months);
            $date = $date->addMonths($floor);
            $remaining = $months - $floor;

            if ($remaining > 0) {
                $duration['week'] = ($duration['week'] ?? 0) + ($remaining * 4);
            }
        }

        if (array_key_exists('week', $duration)) {
            $weeks = (float) $duration['week'];
            $floor = (int) floor($weeks);
            $date = $date->addWeeks($floor);
            $remaining = $weeks - $floor;

            if ($remaining > 0) {
                $duration['day'] = ($duration['day'] ?? 0) + (int) round($remaining * 7);
            }
        }

        if (array_key_exists('day', $duration)) {
            $days = (float) $duration['day'];
            $floor = (int) floor($days);
            $date = $date->addDays($floor);
            $remaining = $days - $floor;

            if ($remaining > 0) {
                $duration['hour'] = ($duration['hour'] ?? 0) + (int) round($remaining * 24);
            }
        }

        if (array_key_exists('hour', $duration)) {
            $hours = (float) $duration['hour'];
            $floor = (int) floor($hours);
            $date = $date->addHours($floor);
            $remaining = $hours - $floor;

            if ($remaining > 0) {
                $duration['minute'] = ($duration['minute'] ?? 0) + (int) round($remaining * 60);
            }
        }

        if (array_key_exists('minute', $duration)) {
            $minutes = (float) $duration['minute'];
            $floor = (int) floor($minutes);
            $date = $date->addMinutes($floor);
            $remaining = $minutes - $floor;

            if ($remaining > 0) {
                $duration['second'] = ($duration['second'] ?? 0) + (int) round($remaining * 60);
            }
        }

        if (array_key_exists('second', $duration)) {
            $seconds = (float) $duration['second'];
            $floor = (int) floor($seconds);
            $date = $date->addSeconds($floor);
            $remaining = $seconds - $floor;

            if ($remaining > 0) {
                $duration['millisecond'] = ($duration['millisecond'] ?? 0) + (int) round($remaining * 1000);
            }
        }

        if (array_key_exists('millisecond', $duration)) {
            $date = $date->addMilliseconds((int) floor((float) $duration['millisecond']));
        }

        return [$date, $duration];
    }

    /**
     * Normalize upstream duration aliases to full component names.
     *
     * @param  array<string, int|float>  $duration
     * @return array<string, int|float>
     */
    public static function normalizeUnits(array $duration): array
    {
        foreach ([
            'y' => 'year',
            'mo' => 'month',
            'M' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'm' => 'minute',
            's' => 'second',
            'ms' => 'millisecond',
        ] as $alias => $unit) {
            if (array_key_exists($alias, $duration)) {
                $duration[$unit] = $duration[$alias];
                unset($duration[$alias]);
            }
        }

        return $duration;
    }
}
