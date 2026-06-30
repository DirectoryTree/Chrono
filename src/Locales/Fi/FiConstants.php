<?php

namespace DirectoryTree\Chrono\Locales\Fi;

use DirectoryTree\Chrono\Pattern;

readonly class FiConstants
{
    /**
     * Finnish weekday names and abbreviations mapped to Carbon weekday indexes.
     *
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'sunnuntai' => 0,
        'sunnuntaina' => 0,
        'su' => 0,
        'maanantai' => 1,
        'maanantaina' => 1,
        'ma' => 1,
        'tiistai' => 2,
        'tiistaina' => 2,
        'ti' => 2,
        'keskiviikko' => 3,
        'keskiviikkona' => 3,
        'ke' => 3,
        'torstai' => 4,
        'torstaina' => 4,
        'to' => 4,
        'perjantai' => 5,
        'perjantaina' => 5,
        'pe' => 5,
        'lauantai' => 6,
        'lauantaina' => 6,
        'la' => 6,
    ];

    /**
     * Finnish month names and common stems mapped to month numbers.
     *
     * @var array<string, int>
     */
    public const MONTHS = [
        'tammikuu' => 1,
        'tammikuuta' => 1,
        'tammikuun' => 1,
        'tammi' => 1,
        'helmikuu' => 2,
        'helmikuuta' => 2,
        'helmikuun' => 2,
        'helmi' => 2,
        'maaliskuu' => 3,
        'maaliskuuta' => 3,
        'maaliskuun' => 3,
        'maalis' => 3,
        'huhtikuu' => 4,
        'huhtikuuta' => 4,
        'huhtikuun' => 4,
        'huhti' => 4,
        'toukokuu' => 5,
        'toukokuuta' => 5,
        'toukokuun' => 5,
        'touko' => 5,
        'kesäkuu' => 6,
        'kesäkuuta' => 6,
        'kesäkuun' => 6,
        'kesä' => 6,
        'heinäkuu' => 7,
        'heinäkuuta' => 7,
        'heinäkuun' => 7,
        'heinä' => 7,
        'elokuu' => 8,
        'elokuuta' => 8,
        'elokuun' => 8,
        'elo' => 8,
        'syyskuu' => 9,
        'syyskuuta' => 9,
        'syyskuun' => 9,
        'syys' => 9,
        'lokakuu' => 10,
        'lokakuuta' => 10,
        'lokakuun' => 10,
        'loka' => 10,
        'marraskuu' => 11,
        'marraskuuta' => 11,
        'marraskuun' => 11,
        'marras' => 11,
        'joulukuu' => 12,
        'joulukuuta' => 12,
        'joulukuun' => 12,
        'joulu' => 12,
    ];

    /**
     * Finnish integer words mapped to numeric values.
     *
     * @var array<string, int>
     */
    public const INTEGERS = [
        'yksi' => 1,
        'yhden' => 1,
        'kaksi' => 2,
        'kahden' => 2,
        'kolme' => 3,
        'kolmen' => 3,
        'neljä' => 4,
        'neljän' => 4,
        'viisi' => 5,
        'viiden' => 5,
        'kuusi' => 6,
        'kuuden' => 6,
        'seitsemän' => 7,
        'kahdeksan' => 8,
        'yhdeksän' => 9,
        'kymmenen' => 10,
    ];

    /**
     * Finnish time-unit words mapped to Chrono duration units.
     *
     * @var array<string, string>
     */
    public const TIME_UNITS = [
        's' => 'second',
        'sek' => 'second',
        'sekunti' => 'second',
        'sekuntia' => 'second',
        'sekunnin' => 'second',
        'min' => 'minute',
        'minuutti' => 'minute',
        'minuuttia' => 'minute',
        'minuutin' => 'minute',
        't' => 'hour',
        'tunti' => 'hour',
        'tuntia' => 'hour',
        'tunnin' => 'hour',
        'pv' => 'day',
        'päivä' => 'day',
        'päivää' => 'day',
        'päivän' => 'day',
        'vk' => 'week',
        'viikko' => 'week',
        'viikkoa' => 'week',
        'viikon' => 'week',
        'kk' => 'month',
        'kuukausi' => 'month',
        'kuukautta' => 'month',
        'kuukauden' => 'month',
        'vuosi' => 'year',
        'vuotta' => 'year',
        'vuoden' => 'year',
    ];

    /**
     * Finnish non-abbreviated time-unit words.
     *
     * @var array<string, string>
     */
    public const TIME_UNITS_WITHOUT_ABBREVIATIONS = [
        'sekunti' => 'second',
        'sekuntia' => 'second',
        'sekunnin' => 'second',
        'minuutti' => 'minute',
        'minuuttia' => 'minute',
        'minuutin' => 'minute',
        'tunti' => 'hour',
        'tuntia' => 'hour',
        'tunnin' => 'hour',
        'päivä' => 'day',
        'päivää' => 'day',
        'päivän' => 'day',
        'viikko' => 'week',
        'viikkoa' => 'week',
        'viikon' => 'week',
        'kuukausi' => 'month',
        'kuukautta' => 'month',
        'kuukauden' => 'month',
        'vuosi' => 'year',
        'vuotta' => 'year',
        'vuoden' => 'year',
    ];

    /**
     * Build a regex alternation for Finnish month names.
     */
    public static function monthPattern(): string
    {
        return Pattern::matchAny(self::MONTHS);
    }

    /**
     * Resolve a Finnish month name to its month number.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[mb_strtolower($month)] ?? null;
    }

    /**
     * Build a regex alternation for Finnish weekday names.
     */
    public static function weekdayPattern(): string
    {
        return Pattern::matchAny(self::WEEKDAYS);
    }

    /**
     * Resolve a Finnish weekday name to its Carbon weekday index.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[mb_strtolower($weekday)] ?? null;
    }

    /**
     * Build a regex alternation for Finnish number words.
     */
    public static function numberPattern(): string
    {
        return '(?:'.self::alternation(array_keys(self::INTEGERS)).'|[0-9]+)';
    }

    /**
     * Resolve a Finnish number word or numeric token.
     */
    public static function number(string $number): int
    {
        $number = mb_strtolower($number);

        return self::INTEGERS[$number] ?? (int) $number;
    }

    /**
     * Build a regex alternation for Finnish time units.
     */
    public static function timeUnitPattern(bool $allowAbbreviations = true): string
    {
        return self::alternation(array_keys($allowAbbreviations ? self::TIME_UNITS : self::TIME_UNITS_WITHOUT_ABBREVIATIONS));
    }

    /**
     * Resolve a Finnish time unit to Chrono's internal duration unit.
     */
    public static function timeUnit(string $unit): ?string
    {
        return self::TIME_UNITS[mb_strtolower($unit)] ?? null;
    }

    /**
     * Build a longest-first regex alternation.
     *
     * @param  array<int, string>  $words
     */
    protected static function alternation(array $words): string
    {
        return Pattern::matchAny($words);
    }
}
