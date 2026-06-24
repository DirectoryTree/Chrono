<?php

namespace Chrono\Locales\En;

use Chrono\Pattern;

class EnConstants
{
    /**
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'sunday' => 0, 'sun' => 0, 'sun.' => 0,
        'monday' => 1, 'mon' => 1, 'mon.' => 1,
        'tuesday' => 2, 'tue' => 2, 'tue.' => 2, 'tues' => 2,
        'wednesday' => 3, 'wed' => 3, 'wed.' => 3,
        'thursday' => 4, 'thu' => 4, 'thu.' => 4, 'thur' => 4, 'thur.' => 4, 'thurs' => 4, 'thurs.' => 4,
        'friday' => 5, 'fri' => 5, 'fri.' => 5,
        'saturday' => 6, 'sat' => 6, 'sat.' => 6,
    ];

    /**
     * @var array<string, int>
     */
    public const MONTHS = [
        'jan' => 1, 'january' => 1,
        'feb' => 2, 'february' => 2,
        'mar' => 3, 'march' => 3,
        'apr' => 4, 'april' => 4,
        'may' => 5,
        'jun' => 6, 'june' => 6,
        'jul' => 7, 'july' => 7,
        'aug' => 8, 'august' => 8,
        'sep' => 9, 'sept' => 9, 'september' => 9,
        'oct' => 10, 'october' => 10,
        'nov' => 11, 'november' => 11,
        'dec' => 12, 'december' => 12,
    ];

    /**
     * Regular expression fragment for English year expressions.
     */
    public const YEAR_PATTERN = '(?:[1-9][0-9]{0,3}\s{0,2}(?:BE|AD|BC|BCE|CE)|[1-9][0-9]{3}|[5-9][0-9]|2[0-5])';

    /**
     * Build the English weekday pattern.
     */
    public static function weekdayPattern(): string
    {
        return Pattern::matchAny(self::WEEKDAYS);
    }

    /**
     * Build the English month pattern.
     */
    public static function monthPattern(): string
    {
        return Pattern::matchAny(self::MONTHS);
    }

    /**
     * Parse an English year expression.
     */
    public static function parseYear(string $year): int
    {
        if (preg_match('/BE/i', $year) === 1) {
            return (int) preg_replace('/BE/i', '', $year) - 543;
        }

        if (preg_match('/BCE?/i', $year) === 1) {
            return -((int) preg_replace('/BCE?/i', '', $year));
        }

        if (preg_match('/(?:AD|CE)/i', $year) === 1) {
            return (int) preg_replace('/(?:AD|CE)/i', '', $year);
        }

        $year = (int) $year;

        if ($year < 100) {
            return $year > 50 ? 1900 + $year : 2000 + $year;
        }

        return $year;
    }
}
