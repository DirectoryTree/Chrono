<?php

namespace DirectoryTree\Chrono\Locales\Nl;

use DirectoryTree\Chrono\Pattern;

readonly class NlConstants
{
    /**
     * Dutch weekday names and abbreviations mapped to Carbon weekday indexes.
     *
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'zondag' => 0, 'zon' => 0, 'zon.' => 0, 'zo' => 0, 'zo.' => 0,
        'maandag' => 1, 'ma' => 1, 'ma.' => 1,
        'dinsdag' => 2, 'din' => 2, 'din.' => 2, 'di' => 2, 'di.' => 2,
        'woensdag' => 3, 'woe' => 3, 'woe.' => 3, 'wo' => 3, 'wo.' => 3,
        'donderdag' => 4, 'dond' => 4, 'dond.' => 4, 'do' => 4, 'do.' => 4,
        'vrijdag' => 5, 'vrij' => 5, 'vrij.' => 5, 'vr' => 5, 'vr.' => 5,
        'zaterdag' => 6, 'zat' => 6, 'zat.' => 6, 'za' => 6, 'za.' => 6,
    ];

    /**
     * Dutch month names and abbreviations mapped to month numbers.
     *
     * @var array<string, int>
     */
    public const MONTHS = [
        'januari' => 1, 'jan' => 1, 'jan.' => 1,
        'februari' => 2, 'feb' => 2, 'feb.' => 2,
        'maart' => 3, 'mar' => 3, 'mar.' => 3, 'mrt' => 3, 'mrt.' => 3,
        'april' => 4, 'apr' => 4, 'apr.' => 4,
        'mei' => 5,
        'juni' => 6, 'jun' => 6, 'jun.' => 6,
        'juli' => 7, 'jul' => 7, 'jul.' => 7,
        'augustus' => 8, 'aug' => 8, 'aug.' => 8,
        'september' => 9, 'sep' => 9, 'sep.' => 9, 'sept' => 9, 'sept.' => 9,
        'oktober' => 10, 'okt' => 10, 'okt.' => 10,
        'november' => 11, 'nov' => 11, 'nov.' => 11,
        'december' => 12, 'dec' => 12, 'dec.' => 12,
    ];

    /**
     * Dutch ordinal day words mapped to day numbers.
     *
     * @var array<string, int>
     */
    public const ORDINALS = [
        'eerste' => 1,
        'tweede' => 2,
        'derde' => 3,
        'vierde' => 4,
        'vijfde' => 5,
        'zesde' => 6,
        'zevende' => 7,
        'achtste' => 8,
        'negende' => 9,
        'tiende' => 10,
        'elfde' => 11,
        'twaalfde' => 12,
        'dertiende' => 13,
        'veertiende' => 14,
        'vijftiende' => 15,
        'zestiende' => 16,
        'zeventiende' => 17,
        'achttiende' => 18,
        'negentiende' => 19,
        'twintigste' => 20,
        'eenentwintigste' => 21,
        'tweeentwintigste' => 22,
        'tweeëntwintigste' => 22,
        'drieentwintigste' => 23,
        'vierentwintigste' => 24,
        'vijfentwintigste' => 25,
        'zesentwintigste' => 26,
        'zevenentwintigste' => 27,
        'achtentwintigste' => 28,
        'negenentwintigste' => 29,
        'dertigste' => 30,
        'eenendertigste' => 31,
    ];

    /**
     * Dutch integer words mapped to numeric values.
     *
     * @var array<string, int>
     */
    public const INTEGERS = [
        'een' => 1,
        'twee' => 2,
        'drie' => 3,
        'vier' => 4,
        'vijf' => 5,
        'zes' => 6,
        'zeven' => 7,
        'acht' => 8,
        'negen' => 9,
        'tien' => 10,
        'elf' => 11,
        'twaalf' => 12,
    ];

    /**
     * Dutch time-unit words mapped to Chrono duration units.
     *
     * @var array<string, string>
     */
    public const TIME_UNITS = [
        'sec' => 'second',
        'second' => 'second',
        'seconden' => 'second',
        'min' => 'minute',
        'mins' => 'minute',
        'minute' => 'minute',
        'minuut' => 'minute',
        'minuten' => 'minute',
        'minuutje' => 'minute',
        'h' => 'hour',
        'hr' => 'hour',
        'hrs' => 'hour',
        'uur' => 'hour',
        'u' => 'hour',
        'uren' => 'hour',
        'dag' => 'day',
        'dagen' => 'day',
        'week' => 'week',
        'weken' => 'week',
        'maand' => 'month',
        'maanden' => 'month',
        'jaar' => 'year',
        'jr' => 'year',
        'jaren' => 'year',
    ];

    /**
     * Build a regex alternation for known Dutch month names.
     */
    public static function monthPattern(): string
    {
        return self::alternation(array_keys(self::MONTHS));
    }

    /**
     * Resolve a matched Dutch month name to its month number.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[mb_strtolower($month)] ?? null;
    }

    /**
     * Build a regex alternation for known Dutch weekday names.
     */
    public static function weekdayPattern(): string
    {
        return self::alternation(array_keys(self::WEEKDAYS));
    }

    /**
     * Resolve a matched Dutch weekday name to its Carbon weekday index.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[mb_strtolower($weekday)] ?? null;
    }

    /**
     * Build a regex alternation for Dutch ordinal day numbers.
     */
    public static function ordinalPattern(): string
    {
        return '(?:'.self::alternation(array_keys(self::ORDINALS)).'|[0-9]{1,2}(?:ste|de)?)';
    }

    /**
     * Resolve a Dutch ordinal word or number to a day number.
     */
    public static function ordinalNumber(string $ordinal): int
    {
        $ordinal = mb_strtolower($ordinal);

        if (isset(self::ORDINALS[$ordinal])) {
            return self::ORDINALS[$ordinal];
        }

        return (int) preg_replace('/(?:ste|de)$/iu', '', $ordinal);
    }

    /**
     * Build a regex pattern for Dutch year values.
     */
    public static function yearPattern(): string
    {
        return '(?:[1-9][0-9]{0,3}\s*(?:voor Christus|na Christus)|[1-2][0-9]{3}|[5-9][0-9])';
    }

    /**
     * Resolve a Dutch year token using upstream era and two-digit year rules.
     */
    public static function year(string $year): int
    {
        if (preg_match('/voor Christus/iu', $year) === 1) {
            return -((int) preg_replace('/voor Christus/iu', '', $year));
        }

        if (preg_match('/na Christus/iu', $year) === 1) {
            return (int) preg_replace('/na Christus/iu', '', $year);
        }

        $year = (int) $year;

        return $year < 100 && $year > 50 ? $year + 1900 : $year;
    }

    /**
     * Build a regex alternation for known Dutch time units.
     */
    public static function timeUnitPattern(): string
    {
        return self::alternation(array_keys(self::TIME_UNITS));
    }

    /**
     * Resolve a Dutch time unit to Chrono's internal duration unit.
     */
    public static function timeUnit(string $unit): ?string
    {
        return self::TIME_UNITS[mb_strtolower($unit)] ?? null;
    }

    /**
     * Build a regex alternation for Dutch number words.
     */
    public static function numberPattern(): string
    {
        return '(?:'.self::alternation(array_keys(self::INTEGERS)).'|[0-9]+|[0-9]+[\.,][0-9]+|halve?|half|paar)';
    }

    /**
     * Resolve a Dutch number word or numeric token.
     */
    public static function number(string $number): float
    {
        $number = mb_strtolower($number);

        if (isset(self::INTEGERS[$number])) {
            return self::INTEGERS[$number];
        }

        if ($number === 'paar') {
            return 2;
        }

        if ($number === 'half' || preg_match('/halve?/iu', $number) === 1) {
            return 0.5;
        }

        return (float) str_replace(',', '.', $number);
    }

    /**
     * Build a longest-first regex alternation for dictionary keys.
     *
     * @param  array<int, string>  $words
     */
    protected static function alternation(array $words): string
    {
        return Pattern::matchAny($words);
    }
}
