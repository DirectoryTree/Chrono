<?php

namespace DirectoryTree\Chrono\Locales\En\Parsers;

use DirectoryTree\Chrono\CasualReferences;
use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class EnCasualDateParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the English casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>now|today|tonight|tomorrow|overmorrow|tmr|tmrw|yesterday|last\s*night)(?=\W|$)';
    }

    /**
     * Extract English casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = strtolower($match['word'][0]);

        $components = match ($word) {
            'now' => CasualReferences::now($reference),
            'today' => CasualReferences::today($reference),
            'yesterday' => CasualReferences::yesterday($reference),
            'tomorrow', 'tmr', 'tmrw' => CasualReferences::tomorrow($reference),
            'tonight' => CasualReferences::tonight($reference),
            'overmorrow' => CasualReferences::theDayAfter($reference, 2),
            default => $this->lastNight($reference),
        };

        return $components->addTag('parser/ENCasualDateParser');
    }

    /**
     * Create English "last night" components.
     */
    protected function lastNight(Reference $reference): ParsedComponents
    {
        $date = $reference->date;
        $components = new ParsedComponents($date, []);

        if ($date->hour > 6) {
            $date = $date->subDay();
        }

        Dates::assignSimilarDate($components, $date);
        $components->imply('hour', 0);

        return $components;
    }
}
