<?php

namespace DirectoryTree\Chrono\Locales\Ru\Parsers;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class RuCasualDateParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Russian casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:с|со)?\s*(?<word>сегодня|вчера|завтра|послезавтра|послепослезавтра|позапозавчера|позавчера)(?=\W|$)';
    }

    /**
     * Extract Russian casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['word'][0]);
        $date = match ($word) {
            'вчера' => $reference->date->subDay(),
            'завтра' => $reference->date->addDay(),
            'послезавтра' => $reference->date->addDays(2),
            'послепослезавтра' => $reference->date->addDays(3),
            'позавчера' => $reference->date->subDays(2),
            'позапозавчера' => $reference->date->subDays(3),
            default => $reference->date,
        };

        $components = new ParsedComponents($date);
        Dates::assignSimilarDate($components, $date);
        Dates::implySimilarTime($components, $date);
        $components->delete('meridiem');
        $components->addTag('parser/RUCasualDateParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Russian casual date parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
