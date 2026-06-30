<?php

namespace DirectoryTree\Chrono\Locales\Pt;

use DirectoryTree\Chrono\Pattern;

readonly class PtConstants
{
    /**
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'domingo' => 0,
        'dom' => 0,
        'segunda' => 1,
        'segunda-feira' => 1,
        'seg' => 1,
        'terça' => 2,
        'terça-feira' => 2,
        'ter' => 2,
        'quarta' => 3,
        'quarta-feira' => 3,
        'qua' => 3,
        'quinta' => 4,
        'quinta-feira' => 4,
        'qui' => 4,
        'sexta' => 5,
        'sexta-feira' => 5,
        'sex' => 5,
        'sábado' => 6,
        'sabado' => 6,
        'sab' => 6,
    ];

    /**
     * @var array<string, int>
     */
    public const MONTHS = [
        'janeiro' => 1,
        'jan' => 1,
        'jan.' => 1,
        'fevereiro' => 2,
        'fev' => 2,
        'fev.' => 2,
        'março' => 3,
        'mar' => 3,
        'mar.' => 3,
        'abril' => 4,
        'abr' => 4,
        'abr.' => 4,
        'maio' => 5,
        'mai' => 5,
        'mai.' => 5,
        'junho' => 6,
        'jun' => 6,
        'jun.' => 6,
        'julho' => 7,
        'jul' => 7,
        'jul.' => 7,
        'agosto' => 8,
        'ago' => 8,
        'ago.' => 8,
        'setembro' => 9,
        'set' => 9,
        'set.' => 9,
        'outubro' => 10,
        'out' => 10,
        'out.' => 10,
        'novembro' => 11,
        'nov' => 11,
        'nov.' => 11,
        'dezembro' => 12,
        'dez' => 12,
        'dez.' => 12,
    ];

    /**
     * Build the Portuguese weekday pattern.
     */
    public static function weekdayPattern(): string
    {
        return self::pattern(array_keys(self::WEEKDAYS));
    }

    /**
     * Resolve a Portuguese weekday token.
     */
    public static function weekdayNumber(string $weekday): ?int
    {
        return self::WEEKDAYS[mb_strtolower($weekday)] ?? null;
    }

    /**
     * Build the Portuguese month pattern.
     */
    public static function monthPattern(): string
    {
        return self::pattern(array_keys(self::MONTHS));
    }

    /**
     * Resolve a Portuguese month token.
     */
    public static function monthNumber(string $month): ?int
    {
        return self::MONTHS[mb_strtolower($month)] ?? null;
    }

    /**
     * Resolve a Portuguese year token.
     */
    public static function year(string $year): int
    {
        if (preg_match('/^[0-9]{1,4}$/', $year) === 1) {
            $number = (int) $year;

            if ($number < 100) {
                return $number > 50 ? 1900 + $number : 2000 + $number;
            }

            return $number;
        }

        if (preg_match('/a\.?\s*c\.?/iu', $year) === 1) {
            return -1 * (int) preg_replace('/\D/', '', $year);
        }

        return (int) $year;
    }

    /**
     * @param  array<int, string>  $words
     */
    protected static function pattern(array $words): string
    {
        return Pattern::matchAny($words);
    }
}
