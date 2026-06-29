<?php

namespace Chrono\Locales\Uk;

use Chrono\Pattern;

readonly class UkConstants
{
    /**
     * Ukrainian weekday names mapped to Carbon weekday indexes.
     */
    public const WEEKDAYS = [
        'неділя' => 0, 'неділі' => 0, 'неділю' => 0, 'нд' => 0, 'нд.' => 0,
        'понеділок' => 1, 'понеділка' => 1, 'пн' => 1, 'пн.' => 1,
        'вівторок' => 2, 'вівторка' => 2, 'вт' => 2, 'вт.' => 2,
        'середа' => 3, 'середи' => 3, 'середу' => 3, 'ср' => 3, 'ср.' => 3,
        'четвер' => 4, 'четверга' => 4, 'четвергу' => 4, 'чт' => 4, 'чт.' => 4,
        "п'ятниця" => 5, "п'ятниці" => 5, "п'ятницю" => 5, 'пт' => 5, 'пт.' => 5,
        'субота' => 6, 'суботи' => 6, 'суботу' => 6, 'сб' => 6, 'сб.' => 6,
    ];

    /**
     * Ukrainian full month names mapped to calendar month numbers.
     */
    public const FULL_MONTHS = [
        'січень' => 1, 'січня' => 1, 'січні' => 1,
        'лютий' => 2, 'лютого' => 2, 'лютому' => 2,
        'березень' => 3, 'березня' => 3, 'березні' => 3,
        'квітень' => 4, 'квітня' => 4, 'квітні' => 4,
        'травень' => 5, 'травня' => 5, 'травні' => 5,
        'червень' => 6, 'червня' => 6, 'червні' => 6,
        'липень' => 7, 'липня' => 7, 'липні' => 7,
        'серпень' => 8, 'серпня' => 8, 'серпні' => 8,
        'вересень' => 9, 'вересня' => 9, 'вересні' => 9,
        'жовтень' => 10, 'жовтня' => 10, 'жовтні' => 10,
        'листопад' => 11, 'листопада' => 11, 'листопаду' => 11,
        'грудень' => 12, 'грудня' => 12, 'грудні' => 12,
    ];

    /**
     * Ukrainian month names and abbreviations mapped to calendar month numbers.
     */
    public const MONTHS = [
        ...self::FULL_MONTHS,
        'січ' => 1, 'січ.' => 1,
        'лют' => 2, 'лют.' => 2,
        'бер' => 3, 'бер.' => 3,
        'квіт' => 4, 'квіт.' => 4,
        'трав' => 5, 'трав.' => 5,
        'черв' => 6, 'черв.' => 6,
        'лип' => 7, 'лип.' => 7,
        'серп' => 8, 'серп.' => 8, 'сер' => 8, 'cер.' => 8,
        'вер' => 9, 'вер.' => 9, 'верес' => 9, 'верес.' => 9,
        'жовт' => 10, 'жовт.' => 10,
        'листоп' => 11, 'листоп.' => 11,
        'груд' => 12, 'груд.' => 12,
    ];

    /**
     * Ukrainian number words mapped to their numeric values.
     */
    public const NUMBERS = [
        'один' => 1, 'одна' => 1, 'одної' => 1, 'одну' => 1,
        'дві' => 2, 'два' => 2, 'двох' => 2,
        'три' => 3, 'трьох' => 3,
        'чотири' => 4, 'чотирьох' => 4,
        "п'ять" => 5, "п'яти" => 5,
        'шість' => 6, 'шести' => 6,
        'сім' => 7, 'семи' => 7,
        'вісім' => 8, 'восьми' => 8,
        "дев'ять" => 9, "дев'яти" => 9,
        'десять' => 10, 'десяти' => 10,
        'одинадцять' => 11, 'одинадцяти' => 11,
        'дванадцять' => 12, 'дванадцяти' => 12,
    ];

    /**
     * Ukrainian ordinal day words mapped to calendar days.
     */
    public const ORDINALS = [
        'перше' => 1, 'першого' => 1, 'друге' => 2, 'другого' => 2, 'третє' => 3, 'третього' => 3,
        'четверте' => 4, 'четвертого' => 4, "п'яте" => 5, "п'ятого" => 5, 'шосте' => 6, 'шостого' => 6,
        'сьоме' => 7, 'сьомого' => 7, 'восьме' => 8, 'восьмого' => 8, "дев'яте" => 9, "дев'ятого" => 9,
        'десяте' => 10, 'десятого' => 10, 'одинадцяте' => 11, 'одинадцятого' => 11,
        'дванадцяте' => 12, 'дванадцятого' => 12, 'тринадцяте' => 13, 'тринадцятого' => 13,
        'чотирнадцяте' => 14, 'чотинрнадцятого' => 14, "п'ятнадцяте" => 15, "п'ятнадцятого" => 15,
        'шістнадцяте' => 16, 'шістнадцятого' => 16, 'сімнадцяте' => 17, 'сімнадцятого' => 17,
        'вісімнадцяте' => 18, 'вісімнадцятого' => 18, "дев'ятнадцяте" => 19, "дев'ятнадцятого" => 19,
        'двадцяте' => 20, 'двадцятого' => 20, 'двадцять перше' => 21, 'двадцять першого' => 21,
        'двадцять друге' => 22, 'двадцять другого' => 22, 'двадцять третє' => 23, 'двадцять третього' => 23,
        'двадцять четверте' => 24, 'двадцять четвертого' => 24, "двадцять п'яте" => 25, "двадцять п'ятого" => 25,
        'двадцять шосте' => 26, 'двадцять шостого' => 26, 'двадцять сьоме' => 27, 'двадцять сьомого' => 27,
        'двадцять восьме' => 28, 'двадцять восьмого' => 28, "двадцять дев'яте" => 29, "двадцять дев'ятого" => 29,
        'тридцяте' => 30, 'тридцятого' => 30, 'тридцять перше' => 31, 'тридцять першого' => 31,
    ];

    /**
     * Ukrainian duration unit words mapped to normalized component names.
     */
    public const TIME_UNITS = [
        'сек' => 'second', 'секунда' => 'second', 'секунд' => 'second', 'секунди' => 'second', 'секунду' => 'second',
        'секундочок' => 'second', 'секундочки' => 'second', 'секундочку' => 'second',
        'хв' => 'minute', 'хвилина' => 'minute', 'хвилин' => 'minute', 'хвилини' => 'minute', 'хвилину' => 'minute',
        'хвилинок' => 'minute', 'хвилинки' => 'minute', 'хвилинку' => 'minute', 'хвилиночок' => 'minute', 'хвилиночки' => 'minute', 'хвилиночку' => 'minute',
        'год' => 'hour', 'година' => 'hour', 'годин' => 'hour', 'години' => 'hour', 'годину' => 'hour',
        'годинка' => 'hour', 'годинок' => 'hour', 'годинки' => 'hour', 'годинку' => 'hour',
        'день' => 'day', 'дня' => 'day', 'днів' => 'day', 'дні' => 'day', 'доба' => 'day', 'добу' => 'day',
        'тиждень' => 'week', 'тижню' => 'week', 'тижня' => 'week', 'тижні' => 'week', 'тижнів' => 'week',
        'місяць' => 'month', 'місяців' => 'month', 'місяці' => 'month', 'місяця' => 'month',
        'квартал' => 'quarter', 'кварталу' => 'quarter', 'квартала' => 'quarter', 'кварталів' => 'quarter', 'кварталі' => 'quarter',
        'рік' => 'year', 'року' => 'year', 'році' => 'year', 'років' => 'year', 'роки' => 'year',
    ];

    /**
     * Build a regex alternation for Ukrainian weekday names.
     */
    public static function weekdayPattern(): string
    {
        return self::pattern(array_keys(self::WEEKDAYS));
    }

    /**
     * Build a regex alternation for Ukrainian month names.
     */
    public static function monthPattern(): string
    {
        return self::pattern(array_keys(self::MONTHS));
    }

    /**
     * Build a regex alternation for Ukrainian number words and digits.
     */
    public static function numberPattern(): string
    {
        return self::pattern(array_keys(self::NUMBERS)).'|[0-9]+(?:\.[0-9]+)?|пів|декілька|пару|\s{0,3}';
    }

    /**
     * Build a regex alternation for Ukrainian ordinal day words and digits.
     */
    public static function ordinalPattern(): string
    {
        return self::pattern(array_keys(self::ORDINALS)).'|[0-9]{1,2}(?:го|ого|е)?';
    }

    /**
     * Build a regex alternation for Ukrainian duration units.
     */
    public static function timeUnitPattern(): string
    {
        return self::pattern(array_keys(self::TIME_UNITS));
    }

    /**
     * Normalize a Ukrainian number word or digit string.
     */
    public static function number(string $number): float
    {
        $number = trim(mb_strtolower($number));

        if (array_key_exists($number, self::NUMBERS)) {
            return self::NUMBERS[$number];
        }

        return match (true) {
            $number === '' => 1,
            str_contains($number, 'декілька'), str_contains($number, 'пар') => 2,
            str_contains($number, 'пів') => 0.5,
            default => (float) $number,
        };
    }

    /**
     * Normalize a Ukrainian ordinal word or digit string.
     */
    public static function ordinal(string $number): int
    {
        return self::ORDINALS[mb_strtolower($number)] ?? (int) preg_replace('/\D/u', '', $number);
    }

    /**
     * Normalize a Ukrainian year string.
     */
    public static function year(string $year): int
    {
        $beforeCommonEra = preg_match('/до\s+н\.?\s*е\.?/iu', $year) === 1;
        $year = (int) preg_replace('/\D/u', '', $year);

        if ($beforeCommonEra) {
            return -$year;
        }

        return $year < 100 ? ($year > 50 ? 1900 + $year : 2000 + $year) : $year;
    }

    /**
     * Build a longest-first regex alternation for literal words.
     *
     * @param  array<int, string>  $words
     */
    protected static function pattern(array $words): string
    {
        return Pattern::matchAny($words);
    }
}
