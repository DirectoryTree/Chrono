<?php

namespace DirectoryTree\Chrono\Locales\Vi;

use DirectoryTree\Chrono\Pattern;

readonly class ViConstants
{
    /**
     * @var array<string, int>
     */
    public const WEEKDAYS = [
        'chủ nhật' => 0, 'cn' => 0,
        'thứ hai' => 1, 't2' => 1,
        'thứ ba' => 2, 't3' => 2,
        'thứ tư' => 3, 't4' => 3,
        'thứ năm' => 4, 't5' => 4,
        'thứ sáu' => 5, 't6' => 5,
        'thứ bảy' => 6, 't7' => 6,
    ];

    /**
     * @var array<string, int>
     */
    public const MONTHS = [
        'tháng 1' => 1, 'tháng một' => 1, 'tháng giêng' => 1,
        'tháng 2' => 2, 'tháng hai' => 2,
        'tháng 3' => 3, 'tháng ba' => 3,
        'tháng 4' => 4, 'tháng tư' => 4,
        'tháng 5' => 5, 'tháng năm' => 5,
        'tháng 6' => 6, 'tháng sáu' => 6,
        'tháng 7' => 7, 'tháng bảy' => 7,
        'tháng 8' => 8, 'tháng tám' => 8,
        'tháng 9' => 9, 'tháng chín' => 9,
        'tháng 10' => 10, 'tháng mười' => 10,
        'tháng 11' => 11, 'tháng mười một' => 11,
        'tháng 12' => 12, 'tháng mười hai' => 12, 'tháng chạp' => 12,
    ];

    /**
     * @var array<string, int>
     */
    public const NUMBERS = [
        'một' => 1, 'hai' => 2, 'ba' => 3, 'bốn' => 4, 'năm' => 5, 'sáu' => 6,
        'bảy' => 7, 'tám' => 8, 'chín' => 9, 'mười' => 10, 'mười một' => 11, 'mười hai' => 12,
    ];

    /**
     * @var array<string, string>
     */
    public const TIME_UNITS = [
        'giây' => 'second',
        'phút' => 'minute',
        'giờ' => 'hour',
        'ngày' => 'day',
        'tuần' => 'week',
        'tháng' => 'month',
        'năm' => 'year',
    ];

    /**
     * Build the Vietnamese weekday pattern.
     */
    public static function weekdayPattern(): string
    {
        return self::pattern(array_keys(self::WEEKDAYS));
    }

    /**
     * Build the Vietnamese month pattern.
     */
    public static function monthPattern(): string
    {
        return self::pattern(array_keys(self::MONTHS));
    }

    /**
     * Build the Vietnamese number pattern.
     */
    public static function numberPattern(): string
    {
        return self::pattern(array_keys(self::NUMBERS)).'|[0-9]+(?:\.[0-9]+)?';
    }

    /**
     * Build the Vietnamese time-unit pattern.
     */
    public static function timeUnitPattern(): string
    {
        return self::pattern(array_keys(self::TIME_UNITS));
    }

    /**
     * Resolve a Vietnamese number token.
     */
    public static function number(string $number): float
    {
        $number = mb_strtolower($number);

        return self::NUMBERS[$number] ?? (float) $number;
    }

    /**
     * Resolve a Vietnamese year token.
     */
    public static function year(string $year): int
    {
        $isBc = preg_match('/TCN/iu', $year) === 1;
        $number = (int) preg_replace('/[^0-9]/', '', $year);

        if ($isBc) {
            return -$number;
        }

        if ($number < 100) {
            return $number > 50 ? 1900 + $number : 2000 + $number;
        }

        return $number;
    }

    /**
     * @param  array<int, string>  $words
     */
    protected static function pattern(array $words): string
    {
        return Pattern::matchAny($words);
    }
}
