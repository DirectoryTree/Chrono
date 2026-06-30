<?php

namespace DirectoryTree\Chrono\Locales\Ja;

use DirectoryTree\Chrono\Reference;

readonly class JaConstants
{
    /**
     * @var array<string, int>
     */
    public const NUMBERS = [
        '零' => 0,
        '〇' => 0,
        '一' => 1,
        '二' => 2,
        '三' => 3,
        '四' => 4,
        '五' => 5,
        '六' => 6,
        '七' => 7,
        '八' => 8,
        '九' => 9,
        '十' => 10,
    ];

    /**
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        '日' => 0,
        '月' => 1,
        '火' => 2,
        '水' => 3,
        '木' => 4,
        '金' => 5,
        '土' => 6,
    ];

    /**
     * Convert full-width Japanese text to half-width text.
     */
    public static function toHankaku(string $text): string
    {
        return mb_convert_kana($text, 'as', 'UTF-8');
    }

    /**
     * Resolve a Japanese numeric token.
     */
    public static function number(string $text): int
    {
        $text = self::toHankaku($text);

        if (is_numeric($text)) {
            return (int) $text;
        }

        $number = 0;
        $length = mb_strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $character = mb_substr($text, $i, 1);

            if ($character === '十') {
                $number = $number === 0 ? 10 : $number * 10;

                continue;
            }

            $number += self::NUMBERS[$character] ?? 0;
        }

        return $number;
    }

    /**
     * Find the year that places the month and day closest to the reference date.
     */
    public static function closestYear(Reference $reference, int $day, int $month): int
    {
        $date = $reference->date->month($month)->day($day);
        $nextYear = $date->addYear();
        $lastYear = $date->subYear();

        $currentDiff = abs($date->getTimestamp() - $reference->date->getTimestamp());
        $nextDiff = abs($nextYear->getTimestamp() - $reference->date->getTimestamp());
        $lastDiff = abs($lastYear->getTimestamp() - $reference->date->getTimestamp());

        if ($nextDiff < $currentDiff) {
            return $nextYear->year;
        }

        if ($lastDiff < $currentDiff) {
            return $lastYear->year;
        }

        return $date->year;
    }
}
