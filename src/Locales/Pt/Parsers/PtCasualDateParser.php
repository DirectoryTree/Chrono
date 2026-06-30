<?php

namespace DirectoryTree\Chrono\Locales\Pt\Parsers;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class PtCasualDateParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Portuguese casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>agora|hoje|amanha|amanhã|ontem)(?=\W|$)';
    }

    /**
     * Extract Portuguese casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['word'][0]);

        $date = match ($word) {
            'amanha', 'amanhã' => $reference->date->addDay(),
            'ontem' => $reference->date->subDay(),
            default => $reference->date,
        };

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ];

        if ($word === 'agora') {
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

        if ($word !== 'agora') {
            Dates::implySimilarTime($components, $date);
            $components->delete('meridiem');
        }

        foreach ($known as $component => $value) {
            $components->assign($component, $value);
        }

        $components->addTag('parser/PTCasualDateParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Portuguese parsers.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
