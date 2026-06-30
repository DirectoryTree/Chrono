<?php

namespace DirectoryTree\Chrono\Locales\Es;

use DirectoryTree\Chrono\Pattern;

readonly class EsConstants
{
    /**
     * @var array<string, int>
     */
    public const MONTHS = [
        'enero' => 1,
        'ene' => 1,
        'ene.' => 1,
        'febrero' => 2,
        'feb' => 2,
        'feb.' => 2,
        'marzo' => 3,
        'mar' => 3,
        'mar.' => 3,
        'abril' => 4,
        'abr' => 4,
        'abr.' => 4,
        'mayo' => 5,
        'may' => 5,
        'may.' => 5,
        'junio' => 6,
        'jun' => 6,
        'jun.' => 6,
        'julio' => 7,
        'jul' => 7,
        'jul.' => 7,
        'agosto' => 8,
        'ago' => 8,
        'ago.' => 8,
        'septiembre' => 9,
        'setiembre' => 9,
        'sep' => 9,
        'sep.' => 9,
        'sept' => 9,
        'octubre' => 10,
        'oct' => 10,
        'oct.' => 10,
        'noviembre' => 11,
        'nov' => 11,
        'nov.' => 11,
        'diciembre' => 12,
        'dic' => 12,
        'dic.' => 12,
    ];

    /**
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'domingo' => 0,
        'dom' => 0,
        'lunes' => 1,
        'lun' => 1,
        'martes' => 2,
        'mar' => 2,
        'miércoles' => 3,
        'miercoles' => 3,
        'mié' => 3,
        'mie' => 3,
        'jueves' => 4,
        'jue' => 4,
        'viernes' => 5,
        'vie' => 5,
        'sábado' => 6,
        'sabado' => 6,
        'sáb' => 6,
        'sab' => 6,
    ];

    /**
     * Build the Spanish weekday pattern.
     */
    public static function weekdayPattern(): string
    {
        return Pattern::matchAny(self::WEEKDAYS);
    }

    /**
     * Build the Spanish month pattern.
     */
    public static function monthPattern(): string
    {
        return Pattern::matchAny(self::MONTHS);
    }

    /**
     * Resolve a Spanish weekday token.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[mb_strtolower($weekday)] ?? null;
    }

    /**
     * Resolve a Spanish month token.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[self::normalize($month)] ?? null;
    }

    /**
     * Normalize a Spanish dictionary token.
     */
    public static function normalize(string $value): string
    {
        return strtr(strtolower(str_replace(['.', ' '], '', $value)), [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
        ]);
    }
}
