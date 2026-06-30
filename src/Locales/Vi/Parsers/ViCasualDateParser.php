<?php

namespace DirectoryTree\Chrono\Locales\Vi\Parsers;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ViCasualDateParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Vietnamese casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>hôm nay|hôm qua|hôm kia|ngày mai|ngày kia|bây giờ|lúc này)(?=\W|$)';
    }

    /**
     * Extract Vietnamese casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['word'][0]);

        $date = match ($word) {
            'hôm qua' => $reference->date->subDay(),
            'hôm kia' => $reference->date->subDays(2),
            'ngày mai' => $reference->date->addDay(),
            'ngày kia' => $reference->date->addDays(2),
            default => $reference->date,
        };

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ];

        if (in_array($word, ['bây giờ', 'lúc này'], true)) {
            $known = [
                ...$known,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
                'millisecond' => $date->millisecond,
                'timezoneOffset' => $date->offsetMinutes,
            ];
        }

        $components = new ParsedComponents($date);

        if (! in_array($word, ['bây giờ', 'lúc này'], true)) {
            Dates::implySimilarTime($components, $date);
            $components->delete('meridiem');
        }

        foreach ($known as $component => $value) {
            $components->assign($component, $value);
        }

        $components->addTag('parser/VICasualDateParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Vietnamese casual date parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
