<?php

namespace Chrono\Locales\Nl\Parsers;

use Chrono\Dates;
use Chrono\Locales\Nl\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class NlCasualDateParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Dutch casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>nu|vandaag|morgen|morgend|gisteren)(?=\W|$)';
    }

    /**
     * Extract Dutch casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['word'][0]);
        $date = match ($word) {
            'morgen', 'morgend' => $reference->date->addDay(),
            'gisteren' => $reference->date->subDay(),
            default => $reference->date,
        };

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ];

        if ($word === 'nu') {
            $known = [
                ...$known,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
                'millisecond' => $date->millisecond,
                'timezoneOffset' => $date->offsetMinutes,
            ];
        }

        $components = $this->components($date, $known);

        if ($word !== 'nu') {
            Dates::implySimilarTime($components, $date);
            $components->delete('meridiem');
        }

        $components->addTag('parser/NLCasualDateParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Dutch casual date parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
