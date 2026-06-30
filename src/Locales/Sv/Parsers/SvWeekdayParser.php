<?php

namespace DirectoryTree\Chrono\Locales\Sv\Parsers;

use DirectoryTree\Chrono\Calculation\Weekdays;
use DirectoryTree\Chrono\Locales\Sv\SvConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class SvWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Swedish weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = SvConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:på\\s*?)?(?:(?<modifier>förra|senaste|nästa|kommande)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?<postmodifier>förra|senaste|nästa|kommande)\\s*vecka)?(?=\\W|$)";
    }

    /**
     * Extract Swedish weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = SvConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''))
        );

        $components->addTag('parser/SVWeekdayParser');

        return $components;
    }

    /**
     * Normalize Swedish weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (true) {
            preg_match('/förra|senaste/iu', $modifier) === 1 => 'last',
            preg_match('/nästa|kommande/iu', $modifier) === 1 => 'next',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Swedish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
