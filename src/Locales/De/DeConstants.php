<?php

namespace DirectoryTree\Chrono\Locales\De;

use DirectoryTree\Chrono\Pattern;

readonly class DeConstants
{
    /**
     * The German month dictionary.
     *
     * @var array<string, int>
     */
    public const MONTHS = [
        'januar' => 1,
        'jänner' => 1,
        'janner' => 1,
        'jan' => 1,
        'jan.' => 1,
        'februar' => 2,
        'feber' => 2,
        'feb' => 2,
        'feb.' => 2,
        'märz' => 3,
        'maerz' => 3,
        'marz' => 3,
        'mär' => 3,
        'mär.' => 3,
        'maer' => 3,
        'mrz' => 3,
        'mrz.' => 3,
        'april' => 4,
        'apr' => 4,
        'apr.' => 4,
        'mai' => 5,
        'juni' => 6,
        'jun' => 6,
        'jun.' => 6,
        'juli' => 7,
        'jul' => 7,
        'jul.' => 7,
        'august' => 8,
        'aug' => 8,
        'aug.' => 8,
        'september' => 9,
        'sep' => 9,
        'sep.' => 9,
        'sept' => 9,
        'sept.' => 9,
        'oktober' => 10,
        'okt' => 10,
        'okt.' => 10,
        'november' => 11,
        'nov' => 11,
        'nov.' => 11,
        'dezember' => 12,
        'dez' => 12,
        'dez.' => 12,
    ];

    /**
     * The German weekday dictionary.
     *
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'sonntag' => 0,
        'so' => 0,
        'montag' => 1,
        'mo' => 1,
        'dienstag' => 2,
        'di' => 2,
        'mittwoch' => 3,
        'mi' => 3,
        'donnerstag' => 4,
        'do' => 4,
        'freitag' => 5,
        'fr' => 5,
        'samstag' => 6,
        'sa' => 6,
    ];

    /**
     * Build a regex alternation for German month names.
     */
    public static function monthPattern(): string
    {
        return Pattern::matchAny(self::MONTHS);
    }

    /**
     * Resolve a German month name to its month number.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[self::normalize($month)] ?? null;
    }

    /**
     * Build a regex alternation for German weekday names.
     */
    public static function weekdayPattern(): string
    {
        return Pattern::matchAny(self::WEEKDAYS);
    }

    /**
     * Resolve a German weekday name to its weekday number.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[self::normalize($weekday)] ?? null;
    }

    /**
     * Normalize German dictionary keys.
     */
    public static function normalize(string $value): string
    {
        return strtr(mb_strtolower(str_replace(['.', ' '], '', $value)), [
            'ä' => 'a',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss',
        ]);
    }
}
