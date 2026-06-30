<?php

namespace DirectoryTree\Chrono\Locales\Ru;

use DirectoryTree\Chrono\Pattern;

readonly class RuConstants
{
    /**
     * Russian weekday names mapped to Carbon weekday indexes.
     */
    public const WEEKDAYS = [
        'воскресенье' => 0, 'воскресенья' => 0, 'вск' => 0, 'вск.' => 0,
        'понедельник' => 1, 'понедельника' => 1, 'пн' => 1, 'пн.' => 1,
        'вторник' => 2, 'вторника' => 2, 'вт' => 2, 'вт.' => 2,
        'среда' => 3, 'среды' => 3, 'среду' => 3, 'ср' => 3, 'ср.' => 3,
        'четверг' => 4, 'четверга' => 4, 'чт' => 4, 'чт.' => 4,
        'пятница' => 5, 'пятницу' => 5, 'пятницы' => 5, 'пт' => 5, 'пт.' => 5,
        'суббота' => 6, 'субботу' => 6, 'субботы' => 6, 'сб' => 6, 'сб.' => 6,
    ];

    /**
     * Russian month names mapped to calendar month numbers.
     */
    public const MONTHS = [
        'январь' => 1, 'января' => 1, 'январе' => 1, 'янв' => 1, 'янв.' => 1,
        'февраль' => 2, 'февраля' => 2, 'феврале' => 2, 'фев' => 2, 'фев.' => 2,
        'март' => 3, 'марта' => 3, 'марте' => 3, 'мар' => 3, 'мар.' => 3,
        'апрель' => 4, 'апреля' => 4, 'апреле' => 4, 'апр' => 4, 'апр.' => 4,
        'май' => 5, 'мая' => 5, 'мае' => 5,
        'июнь' => 6, 'июня' => 6, 'июне' => 6,
        'июль' => 7, 'июля' => 7, 'июле' => 7,
        'август' => 8, 'августа' => 8, 'августе' => 8, 'авг' => 8, 'авг.' => 8,
        'сентябрь' => 9, 'сентября' => 9, 'сентябре' => 9, 'сен' => 9, 'сен.' => 9,
        'октябрь' => 10, 'октября' => 10, 'октябре' => 10, 'окт' => 10, 'окт.' => 10,
        'ноябрь' => 11, 'ноября' => 11, 'ноябре' => 11, 'ноя' => 11, 'ноя.' => 11,
        'декабрь' => 12, 'декабря' => 12, 'декабре' => 12, 'дек' => 12, 'дек.' => 12,
    ];

    /**
     * Russian number words mapped to their integer values.
     */
    public const NUMBERS = [
        'один' => 1, 'одна' => 1, 'одной' => 1, 'одну' => 1,
        'две' => 2, 'два' => 2, 'двух' => 2,
        'три' => 3, 'трех' => 3, 'трёх' => 3,
        'четыре' => 4, 'четырех' => 4, 'четырёх' => 4,
        'пять' => 5, 'пяти' => 5, 'шесть' => 6, 'шести' => 6,
        'семь' => 7, 'семи' => 7, 'восемь' => 8, 'восьми' => 8,
        'девять' => 9, 'девяти' => 9, 'десять' => 10, 'десяти' => 10,
        'одиннадцать' => 11, 'одиннадцати' => 11, 'двенадцать' => 12, 'двенадцати' => 12,
    ];

    /**
     * Russian ordinal day words mapped to calendar days.
     */
    public const ORDINALS = [
        'первое' => 1, 'первого' => 1, 'второе' => 2, 'второго' => 2, 'третье' => 3, 'третьего' => 3,
        'четвертое' => 4, 'четвертого' => 4, 'пятое' => 5, 'пятого' => 5, 'шестое' => 6, 'шестого' => 6,
        'седьмое' => 7, 'седьмого' => 7, 'восьмое' => 8, 'восьмого' => 8, 'девятое' => 9, 'девятого' => 9,
        'десятое' => 10, 'десятого' => 10, 'одиннадцатое' => 11, 'одиннадцатого' => 11, 'двенадцатое' => 12, 'двенадцатого' => 12,
        'тринадцатое' => 13, 'тринадцатого' => 13, 'четырнадцатое' => 14, 'четырнадцатого' => 14, 'пятнадцатое' => 15, 'пятнадцатого' => 15,
        'шестнадцатое' => 16, 'шестнадцатого' => 16, 'семнадцатое' => 17, 'семнадцатого' => 17, 'восемнадцатое' => 18, 'восемнадцатого' => 18,
        'девятнадцатое' => 19, 'девятнадцатого' => 19, 'двадцатое' => 20, 'двадцатого' => 20, 'тридцатое' => 30, 'тридцатого' => 30,
        'двадцать первое' => 21, 'двадцать первого' => 21, 'двадцать второе' => 22, 'двадцать второго' => 22, 'двадцать третье' => 23, 'двадцать третьего' => 23,
        'двадцать четвертое' => 24, 'двадцать четвертого' => 24, 'двадцать пятое' => 25, 'двадцать пятого' => 25, 'двадцать шестое' => 26, 'двадцать шестого' => 26,
        'двадцать седьмое' => 27, 'двадцать седьмого' => 27, 'двадцать восьмое' => 28, 'двадцать восьмого' => 28, 'двадцать девятое' => 29, 'двадцать девятого' => 29,
        'тридцать первое' => 31, 'тридцать первого' => 31,
    ];

    /**
     * Russian duration unit words mapped to normalized component names.
     */
    public const TIME_UNITS = [
        'сек' => 'second', 'секунда' => 'second', 'секунд' => 'second', 'секунды' => 'second', 'секунду' => 'second',
        'мин' => 'minute', 'минута' => 'minute', 'минут' => 'minute', 'минуты' => 'minute', 'минуту' => 'minute',
        'час' => 'hour', 'часов' => 'hour', 'часа' => 'hour', 'часу' => 'hour',
        'день' => 'day', 'дня' => 'day', 'дней' => 'day', 'суток' => 'day', 'сутки' => 'day',
        'неделя' => 'week', 'неделе' => 'week', 'недели' => 'week', 'неделю' => 'week', 'недель' => 'week',
        'месяц' => 'month', 'месяце' => 'month', 'месяцев' => 'month', 'месяца' => 'month',
        'квартал' => 'quarter', 'квартале' => 'quarter', 'кварталов' => 'quarter',
        'год' => 'year', 'года' => 'year', 'году' => 'year', 'годов' => 'year', 'лет' => 'year',
    ];

    /**
     * Build a regex alternation for Russian weekday names.
     */
    public static function weekdayPattern(): string
    {
        return self::pattern(array_keys(self::WEEKDAYS));
    }

    /**
     * Build a regex alternation for Russian month names.
     */
    public static function monthPattern(): string
    {
        return self::pattern(array_keys(self::MONTHS));
    }

    /**
     * Build a regex alternation for Russian number words and digits.
     */
    public static function numberPattern(): string
    {
        return self::pattern(array_keys(self::NUMBERS)).'|[0-9]+|[0-9]+\\.[0-9]+|пол|несколько|пар(?:ы|у)|\\s{0,3}';
    }

    /**
     * Build a regex alternation for Russian ordinal day words and digits.
     */
    public static function ordinalPattern(): string
    {
        return self::pattern(array_keys(self::ORDINALS)).'|\d{1,2}(?:-?(?:го|е))?';
    }

    /**
     * Build a regex alternation for Russian duration units.
     */
    public static function timeUnitPattern(): string
    {
        return self::pattern(array_keys(self::TIME_UNITS));
    }

    /**
     * Normalize a Russian number word or digit string.
     */
    public static function number(string $number): int|float
    {
        $number = mb_strtolower(trim($number));

        if ($number === '') {
            return 1;
        }

        if ($number === 'несколько') {
            return 3;
        }

        if ($number === 'пол') {
            return 0.5;
        }

        if (preg_match('/^пар(?:ы|у)$/u', $number) === 1) {
            return 2;
        }

        return self::NUMBERS[$number] ?? (float) $number;
    }

    /**
     * Normalize a Russian ordinal word or digit string.
     */
    public static function ordinal(string $number): int
    {
        return self::ORDINALS[mb_strtolower($number)] ?? (int) preg_replace('/\D/u', '', $number);
    }

    /**
     * Normalize a Russian year string.
     */
    public static function year(string $year): int
    {
        $year = (int) preg_replace('/\D/u', '', $year);

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
