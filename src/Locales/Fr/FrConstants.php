<?php

namespace DirectoryTree\Chrono\Locales\Fr;

use DirectoryTree\Chrono\Pattern;

readonly class FrConstants
{
    /**
     * The French month dictionary.
     *
     * @var array<string, int>
     */
    public const MONTHS = [
        'janvier' => 1,
        'jan' => 1,
        'février' => 2,
        'fevrier' => 2,
        'fév' => 2,
        'fev' => 2,
        'mars' => 3,
        'mar' => 3,
        'avril' => 4,
        'avr' => 4,
        'mai' => 5,
        'juin' => 6,
        'jun' => 6,
        'juillet' => 7,
        'juil' => 7,
        'jul' => 7,
        'août' => 8,
        'aout' => 8,
        'ao' => 8,
        'septembre' => 9,
        'sept' => 9,
        'sep' => 9,
        'octobre' => 10,
        'oct' => 10,
        'novembre' => 11,
        'nov' => 11,
        'décembre' => 12,
        'decembre' => 12,
        'déc' => 12,
        'dec' => 12,
    ];

    /**
     * The French weekday dictionary.
     *
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'dimanche' => 0,
        'dim' => 0,
        'lundi' => 1,
        'lun' => 1,
        'mardi' => 2,
        'mar' => 2,
        'mercredi' => 3,
        'mer' => 3,
        'jeudi' => 4,
        'jeu' => 4,
        'vendredi' => 5,
        'ven' => 5,
        'samedi' => 6,
        'sam' => 6,
    ];

    /**
     * Build a regex alternation for French month names.
     */
    public static function monthPattern(): string
    {
        return Pattern::matchAny(self::MONTHS);
    }

    /**
     * Resolve a French month name to its month number.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[self::normalize($month)] ?? null;
    }

    /**
     * Build a regex alternation for French weekday names.
     */
    public static function weekdayPattern(): string
    {
        return Pattern::matchAny(self::WEEKDAYS);
    }

    /**
     * Resolve a French weekday name to its weekday number.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[self::normalize($weekday)] ?? null;
    }

    /**
     * Normalize French dictionary keys.
     */
    public static function normalize(string $value): string
    {
        return strtr(mb_strtolower(str_replace(['.', ' '], '', $value)), [
            'à' => 'a',
            'â' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'î' => 'i',
            'ï' => 'i',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c',
        ]);
    }
}
