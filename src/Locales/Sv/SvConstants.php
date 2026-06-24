<?php

namespace Chrono\Locales\Sv;

use Chrono\Pattern;

class SvConstants
{
    /**
     * @var array<string, int>
     */
    protected const WEEKDAYS = [
        'söndag' => 0,
        'sön' => 0,
        'so' => 0,
        'måndag' => 1,
        'mån' => 1,
        'må' => 1,
        'tisdag' => 2,
        'tis' => 2,
        'ti' => 2,
        'onsdag' => 3,
        'ons' => 3,
        'on' => 3,
        'torsdag' => 4,
        'tors' => 4,
        'to' => 4,
        'fredag' => 5,
        'fre' => 5,
        'fr' => 5,
        'lördag' => 6,
        'lör' => 6,
        'lö' => 6,
    ];

    /**
     * @var array<string, int>
     */
    protected const MONTHS = [
        'januari' => 1,
        'jan' => 1,
        'jan.' => 1,
        'februari' => 2,
        'feb' => 2,
        'feb.' => 2,
        'mars' => 3,
        'mar' => 3,
        'mar.' => 3,
        'april' => 4,
        'apr' => 4,
        'apr.' => 4,
        'maj' => 5,
        'juni' => 6,
        'jun' => 6,
        'jun.' => 6,
        'juli' => 7,
        'jul' => 7,
        'jul.' => 7,
        'augusti' => 8,
        'aug' => 8,
        'aug.' => 8,
        'september' => 9,
        'sep' => 9,
        'sep.' => 9,
        'sept' => 9,
        'oktober' => 10,
        'okt' => 10,
        'okt.' => 10,
        'november' => 11,
        'nov' => 11,
        'nov.' => 11,
        'december' => 12,
        'dec' => 12,
        'dec.' => 12,
    ];

    /**
     * @var array<string, int>
     */
    protected const NUMBERS = [
        'en' => 1,
        'ett' => 1,
        'två' => 2,
        'tre' => 3,
        'fyra' => 4,
        'fem' => 5,
        'sex' => 6,
        'sju' => 7,
        'åtta' => 8,
        'nio' => 9,
        'tio' => 10,
        'elva' => 11,
        'tolv' => 12,
        'tretton' => 13,
        'fjorton' => 14,
        'femton' => 15,
        'sexton' => 16,
        'sjutton' => 17,
        'arton' => 18,
        'nitton' => 19,
        'tjugo' => 20,
        'trettio' => 30,
        'fyrtio' => 40,
        'femtio' => 50,
        'sextio' => 60,
        'sjuttio' => 70,
        'åttio' => 80,
        'nittio' => 90,
        'hundra' => 100,
        'tusen' => 1000,
    ];

    /**
     * @var array<string, string>
     */
    protected const TIME_UNITS = [
        'sek' => 'second',
        'sekund' => 'second',
        'sekunder' => 'second',
        'min' => 'minute',
        'minut' => 'minute',
        'minuter' => 'minute',
        'tim' => 'hour',
        'timme' => 'hour',
        'timmar' => 'hour',
        'dag' => 'day',
        'dagar' => 'day',
        'vecka' => 'week',
        'veckor' => 'week',
        'mån' => 'month',
        'månad' => 'month',
        'månader' => 'month',
        'år' => 'year',
        'kvartal' => 'quarter',
    ];

    /**
     * Build the Swedish weekday pattern.
     */
    public static function weekdayPattern(): string
    {
        return self::pattern(array_keys(self::WEEKDAYS));
    }

    /**
     * Build the Swedish month pattern.
     */
    public static function monthPattern(): string
    {
        return self::pattern(array_keys(self::MONTHS));
    }

    /**
     * Build the Swedish number pattern.
     */
    public static function numberPattern(): string
    {
        return self::pattern(array_keys(self::NUMBERS)).'|\d+';
    }

    /**
     * Build the Swedish time-unit pattern.
     */
    public static function timeUnitPattern(): string
    {
        return self::pattern(array_keys(self::TIME_UNITS));
    }

    /**
     * Resolve a Swedish weekday token.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[mb_strtolower($weekday)] ?? null;
    }

    /**
     * Resolve a Swedish month token.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[mb_strtolower($month)] ?? null;
    }

    /**
     * Resolve a Swedish number token.
     */
    public static function number(string $number): int
    {
        $number = mb_strtolower($number);

        return self::NUMBERS[$number] ?? (int) $number;
    }

    /**
     * Resolve a Swedish time-unit token.
     */
    public static function timeUnit(string $unit): ?string
    {
        return self::TIME_UNITS[mb_strtolower($unit)] ?? null;
    }

    /**
     * @param  array<int, string>  $words
     */
    protected static function pattern(array $words): string
    {
        return Pattern::matchAny($words);
    }
}
