<?php

namespace DirectoryTree\Chrono\Calculation;

use Carbon\CarbonImmutable;

readonly class Years
{
    /**
     * Find the most likely AD year from a raw number.
     */
    public static function findMostLikelyADYear(int $year): int
    {
        if ($year < 100) {
            return $year > 50 ? $year + 1900 : $year + 2000;
        }

        return $year;
    }

    /**
     * Find the year that places the day and month closest to the reference date.
     */
    public static function findYearClosestToReference(CarbonImmutable $reference, int $day, int $month): int
    {
        $date = $reference->month($month)->day($day);
        $nextYear = Duration::add($date, ['year' => 1]);
        $lastYear = Duration::add($date, ['year' => -1]);

        $currentDiff = abs($date->getTimestamp() - $reference->getTimestamp());
        $nextDiff = abs($nextYear->getTimestamp() - $reference->getTimestamp());
        $lastDiff = abs($lastYear->getTimestamp() - $reference->getTimestamp());

        if ($nextDiff < $currentDiff) {
            return $nextYear->year;
        }

        if ($lastDiff < $currentDiff) {
            return $lastYear->year;
        }

        return $date->year;
    }
}
