<?php

namespace Chrono\Locales\Uk\Parsers;

use Chrono\Dates;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class UkCasualDateParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Ukrainian casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:з|із|від)?\s*(?<word>сьогодні|вчора|завтра|післязавтра|післяпіслязавтра|позапозавчора|позавчора)(?=\W|$)';
    }

    /**
     * Extract Ukrainian casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $date = match (mb_strtolower($match['word'][0])) {
            'вчора' => $reference->date->subDay(),
            'завтра' => $reference->date->addDay(),
            'післязавтра' => $reference->date->addDays(2),
            'післяпіслязавтра' => $reference->date->addDays(3),
            'позавчора' => $reference->date->subDays(2),
            'позапозавчора' => $reference->date->subDays(3),
            default => $reference->date,
        };

        $components = new ParsedComponents($date);
        Dates::assignSimilarDate($components, $date);
        Dates::implySimilarTime($components, $date);
        $components->delete('meridiem');
        $components->addTag('parser/UKCasualDateParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Ukrainian casual date parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
