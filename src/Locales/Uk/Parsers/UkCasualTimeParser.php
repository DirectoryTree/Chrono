<?php

namespace DirectoryTree\Chrono\Locales\Uk\Parsers;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class UkCasualTimeParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Ukrainian casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>зараз|минулого\s*вечора|минулої\s*ночі|наступної\s*ночі|сьогодні\s*вночі|цієї\s*ночі|цього ранку|вранці|ранку|зранку|опівдні|ввечері|вечора|опівночі|вночі)(?=\W|$)';
    }

    /**
     * Extract Ukrainian casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['word'][0]);
        $date = $reference->date;

        if ($word === 'зараз') {
            $components = new ParsedComponents($date);
            Dates::assignSimilarDate($components, $date);
            Dates::assignSimilarTime($components, $date);
            $components->assign('timezoneOffset', $date->offsetMinutes);

            return new ParsedResult($match[0][1], trim($match[0][0]), $components->addTag('parser/UKCasualTimeParser'));
        }

        if (preg_match('/минулої\s*ночі/u', $word) === 1) {
            $date = ($date->hour < 6 ? $date->subDay() : $date)->hour(0);
        } elseif (preg_match('/минулого\s*вечора/u', $word) === 1) {
            $date = $date->subDay()->hour(20);
        } elseif (preg_match('/наступної\s*ночі/u', $word) === 1) {
            $date = $date->addDays($date->hour < 22 ? 1 : 2)->hour(1);
        } elseif (str_contains($word, 'ввечері') || $word === 'вечора') {
            $date = $date->hour(20);
        } elseif (str_ends_with($word, 'вранці') || str_ends_with($word, 'ранку') || str_ends_with($word, 'зранку')) {
            $date = $date->hour(6);
        } elseif (str_ends_with($word, 'опівдні')) {
            $date = $date->hour(12);
        } else {
            $date = $date->hour(0);
        }

        $components = new ParsedComponents($date->minute(0)->second(0)->millisecond(0));
        $components->assign('hour', $date->hour)->assign('minute', 0)->addTag('parser/UKCasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Ukrainian casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
