<?php

namespace Chrono\Locales\Zh;

class ZhConstants
{
    /**
     * @var array<string, int>
     */
    public const HANS_NUMBERS = ['零' => 0, '〇' => 0, '一' => 1, '二' => 2, '两' => 2, '三' => 3, '四' => 4, '五' => 5, '六' => 6, '七' => 7, '八' => 8, '九' => 9, '十' => 10];

    /**
     * @var array<string, int>
     */
    public const HANT_NUMBERS = ['零' => 0, '一' => 1, '二' => 2, '兩' => 2, '三' => 3, '四' => 4, '五' => 5, '六' => 6, '七' => 7, '八' => 8, '九' => 9, '十' => 10, '廿' => 20, '卅' => 30];

    /**
     * @var array<string, int>
     */
    public const WEEKDAYS = ['天' => 0, '日' => 0, '一' => 1, '二' => 2, '三' => 3, '四' => 4, '五' => 5, '六' => 6];

    /**
     * @param  array<string, int>  $numbers
     */
    public static function number(string $text, array $numbers): int
    {
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

            $number += $numbers[$character] ?? 0;
        }

        return $number;
    }

    /**
     * @param  array<string, int>  $numbers
     */
    public static function year(string $text, array $numbers): int
    {
        if (is_numeric($text)) {
            return (int) $text;
        }

        $year = '';
        $length = mb_strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $year .= (string) ($numbers[mb_substr($text, $i, 1)] ?? '');
        }

        return (int) $year;
    }
}
