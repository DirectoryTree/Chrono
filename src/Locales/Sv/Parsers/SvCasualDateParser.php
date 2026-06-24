<?php

namespace Chrono\Locales\Sv\Parsers;

use Chrono\CasualReferences;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class SvCasualDateParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Swedish casual date parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<date>nu|idag|imorgon|imorn|övermorgon|igår|förrgår|i\s*förrgår)'.
            '(?:\s*(?:(?:på|vid)\s*)?(?<time>morgonen?|förmiddagen?|middagen?|eftermiddagen?|kvällen?|natten?|midnatt))?'.
            '(?=\W|$)';
    }

    /**
     * Extract Swedish casual date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents
    {
        $keyword = mb_strtolower($match['date'][0]);
        $time = mb_strtolower($match['time'][0] ?? '');

        $components = match ($keyword) {
            'nu' => CasualReferences::now($reference),
            'idag' => CasualReferences::today($reference),
            'imorgon', 'imorn' => CasualReferences::tomorrow($reference),
            'övermorgon' => CasualReferences::theDayAfter($reference, 2),
            'igår' => CasualReferences::yesterday($reference),
            'förrgår', 'i förrgår' => CasualReferences::theDayBefore($reference, 2),
            default => new ParsedComponents($reference->date),
        };

        match ($time) {
            'morgon', 'morgonen' => $this->implyTime($components, 6),
            'förmiddag', 'förmiddagen' => $this->implyTime($components, 9),
            'middag', 'middagen' => $this->implyTime($components, 12),
            'eftermiddag', 'eftermiddagen' => $this->implyTime($components, 15),
            'kväll', 'kvällen' => $this->implyTime($components, 20),
            'natt', 'natten' => $this->implyTime($components, 2),
            'midnatt' => $this->implyTime($components, 0),
            default => null,
        };

        return $components->addTag('parser/SVCasualDateParser');
    }

    /**
     * Imply a day-period time on the parsed components.
     */
    protected function implyTime(ParsedComponents $components, int $hour): void
    {
        $components
            ->imply('hour', $hour)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0);
    }

    /**
     * Use a Unicode-safe left boundary for Swedish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
