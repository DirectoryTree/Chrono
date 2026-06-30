<?php

namespace DirectoryTree\Chrono\Locales\Ru\Parsers;

use DirectoryTree\Chrono\Locales\Ru\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class RuCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Russian casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>сейчас|прошлым\s*вечером|прошлой\s*ночью|следующей\s*ночью|сегодня\s*ночью|этой\s*ночью|ночью|этим утром|утром|утра|в\s*полдень|вечером|вечера|в\s*полночь)(?=\W|$)';
    }

    /**
     * Extract Russian casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['word'][0]);
        $date = $reference->date;
        $known = [];

        if ($word === 'сейчас') {
            $known = [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
                'millisecond' => $date->millisecond,
                'timezoneOffset' => $date->offsetMinutes,
            ];
        } elseif (str_contains($word, 'прошлой ночью')) {
            $date = $date->hour < 6 ? $date->subDay()->hour(0) : $date->hour(0);
            $known = ['hour' => 0, 'minute' => 0];
        } elseif (str_contains($word, 'прошлым вечером')) {
            $date = $date->subDay()->hour(20)->minute(0);
            $known = ['hour' => 20, 'minute' => 0];
        } elseif (str_contains($word, 'полдень')) {
            $date = $date->hour(12)->minute(0);
            $known = ['hour' => 12, 'minute' => 0];
        } elseif (str_contains($word, 'полночь') || str_ends_with($word, 'ночью')) {
            $date = $date->hour > 2 ? $date->addDay()->hour(0) : $date->hour(0);
            $known = ['hour' => 0, 'minute' => 0];
        } elseif (str_contains($word, 'утр')) {
            $date = $date->hour(6)->minute(0);
            $known = ['hour' => 6, 'minute' => 0];
        } elseif (str_contains($word, 'вечер')) {
            $date = $date->hour(20)->minute(0);
            $known = ['hour' => 20, 'minute' => 0];
        }

        return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, $known)->addTag('parser/RUCasualTimeParser'));
    }

    /**
     * Get the Unicode-aware left boundary used by Russian casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
