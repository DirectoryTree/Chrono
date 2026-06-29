<?php

namespace Chrono\Locales\It;

use Chrono\Pattern;

readonly class ItConstants
{
    /**
     * Italian weekday names and abbreviations mapped to Carbon weekday indexes.
     *
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'domenica' => 0,
        'dom' => 0,
        'lunedi' => 1,
        'lunedì' => 1,
        'lun' => 1,
        'martedi' => 2,
        'martedì' => 2,
        'mar' => 2,
        'mercoledi' => 3,
        'mercoledì' => 3,
        'merc' => 3,
        'giovedi' => 4,
        'giovedì' => 4,
        'giov' => 4,
        'venerdi' => 5,
        'venerdì' => 5,
        'ven' => 5,
        'sabato' => 6,
        'sab' => 6,
    ];

    /**
     * Italian month names and abbreviations mapped to month numbers.
     *
     * @var array<string, int>
     */
    public const MONTHS = [
        'gennaio' => 1,
        'gen' => 1,
        'gen.' => 1,
        'febbraio' => 2,
        'feb' => 2,
        'feb.' => 2,
        'febraio' => 2,
        'febb' => 2,
        'febb.' => 2,
        'marzo' => 3,
        'mar' => 3,
        'mar.' => 3,
        'aprile' => 4,
        'apr' => 4,
        'apr.' => 4,
        'maggio' => 5,
        'mag' => 5,
        'giugno' => 6,
        'giu' => 6,
        'luglio' => 7,
        'lug' => 7,
        'lugl' => 7,
        'lug.' => 7,
        'agosto' => 8,
        'ago' => 8,
        'settembre' => 9,
        'set' => 9,
        'set.' => 9,
        'sett' => 9,
        'sett.' => 9,
        'ottobre' => 10,
        'ott' => 10,
        'ott.' => 10,
        'novembre' => 11,
        'nov' => 11,
        'nov.' => 11,
        'dicembre' => 12,
        'dic' => 12,
        'dice' => 12,
        'dic.' => 12,
    ];

    /**
     * Italian ordinal day words mapped to day numbers.
     *
     * @var array<string, int>
     */
    public const ORDINALS = [
        'primo' => 1,
        'secondo' => 2,
        'terzo' => 3,
        'quarto' => 4,
        'quinto' => 5,
        'sesto' => 6,
        'settimo' => 7,
        'ottavo' => 8,
        'nono' => 9,
        'decimo' => 10,
        'undicesimo' => 11,
        'dodicesimo' => 12,
        'tredicesimo' => 13,
        'quattordicesimo' => 14,
        'quindicesimo' => 15,
        'sedicesimo' => 16,
        'diciassettesimo' => 17,
        'diciottesimo' => 18,
        'diciannovesimo' => 19,
        'ventesimo' => 20,
        'ventunesimo' => 21,
        'ventiduesimo' => 22,
        'ventitreesimo' => 23,
        'ventiquattresimo' => 24,
        'venticinquesimo' => 25,
        'ventiseiesimo' => 26,
        'ventisettesimo' => 27,
        'ventottesimo' => 28,
        'ventinovesimo' => 29,
        'trentesimo' => 30,
        'trentunesimo' => 31,
    ];

    /**
     * Italian integer words mapped to numeric values.
     *
     * @var array<string, int>
     */
    public const INTEGERS = [
        'uno' => 1,
        'una' => 1,
        'un' => 1,
        'due' => 2,
        'tre' => 3,
        'quattro' => 4,
        'cinque' => 5,
        'sei' => 6,
        'sette' => 7,
        'otto' => 8,
        'nove' => 9,
        'dieci' => 10,
        'undici' => 11,
        'dodici' => 12,
    ];

    /**
     * Italian time-unit words mapped to Chrono duration units.
     *
     * @var array<string, string>
     */
    public const TIME_UNITS = [
        'sec' => 'second',
        'secondo' => 'second',
        'secondi' => 'second',
        'min' => 'minute',
        'mins' => 'minute',
        'minuti' => 'minute',
        'h' => 'hour',
        'hr' => 'hour',
        'o' => 'hour',
        'ora' => 'hour',
        'ore' => 'hour',
        'giorno' => 'day',
        'giorni' => 'day',
        'settimana' => 'week',
        'settimane' => 'week',
        'mese' => 'month',
        'mesi' => 'month',
        'trimestre' => 'quarter',
        'trimestri' => 'quarter',
        'anno' => 'year',
        'anni' => 'year',
    ];

    /**
     * Build a regex alternation for known Italian month names.
     */
    public static function monthPattern(): string
    {
        return self::alternation(array_keys(self::MONTHS));
    }

    /**
     * Build a regex alternation for known Italian weekday names.
     */
    public static function weekdayPattern(): string
    {
        return Pattern::matchAny(self::WEEKDAYS);
    }

    /**
     * Resolve a matched Italian weekday name to its Carbon weekday index.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[mb_strtolower($weekday)] ?? null;
    }

    /**
     * Resolve a matched Italian month name to its month number.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[mb_strtolower($month)] ?? null;
    }

    /**
     * Build a regex alternation for ordinal day numbers.
     */
    public static function ordinalPattern(): string
    {
        return '(?:'.self::alternation(array_keys(self::ORDINALS)).'|[0-9]{1,2}(?:mo|ndo|rzo|simo|esimo)?)';
    }

    /**
     * Resolve an Italian ordinal word or number to a day number.
     */
    public static function ordinalNumber(string $ordinal): int
    {
        $ordinal = mb_strtolower($ordinal);

        if (isset(self::ORDINALS[$ordinal])) {
            return self::ORDINALS[$ordinal];
        }

        return (int) preg_replace('/(?:imo|ndo|rzo|rto|nto|sto|tavo|nono|cimo|timo|esimo)$/iu', '', $ordinal);
    }

    /**
     * Build a regex alternation for known Italian time units.
     */
    public static function timeUnitPattern(): string
    {
        return self::alternation(array_keys(self::TIME_UNITS));
    }

    /**
     * Resolve an Italian time unit to Chrono's internal duration unit.
     */
    public static function timeUnit(string $unit): ?string
    {
        return self::TIME_UNITS[mb_strtolower($unit)] ?? null;
    }

    /**
     * Build a regex alternation for Italian number words.
     */
    public static function numberPattern(): string
    {
        return '(?:'.self::alternation(array_keys(self::INTEGERS)).'|[0-9]+(?:\.[0-9]+)?|un|una|qualcuno|alcuni|molti|metà|paio)';
    }

    /**
     * Resolve an Italian number word or numeric token.
     */
    public static function number(string $number): float
    {
        $number = mb_strtolower(trim($number));

        if (isset(self::INTEGERS[$number])) {
            return self::INTEGERS[$number];
        }

        if (str_contains($number, 'alcuni')) {
            return 3;
        }

        if (str_contains($number, 'metà')) {
            return 0.5;
        }

        if (str_contains($number, 'paio')) {
            return 2;
        }

        if (str_contains($number, 'molti')) {
            return 7;
        }

        if ($number === 'qualcuno') {
            return 1;
        }

        return (float) $number;
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
