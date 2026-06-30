<?php

namespace DirectoryTree\Chrono\Locales\It\Parsers;

use DirectoryTree\Chrono\Calculation\Weekdays;
use DirectoryTree\Chrono\Locales\It\ItConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ItWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Italian weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = ItConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:il\\s*?)?(?:(?<modifier>questa|l'ultima|scorsa|prossima)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?<postmodifier>questa|l'ultima|scorsa|prossima)\\s*settimana)?(?=\\W|$)";
    }

    /**
     * Extract Italian weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = ItConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''))
        );

        $components->addTag('parser/ITWeekdayParser');

        return $components;
    }

    /**
     * Normalize Italian weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            "l'ultima", 'ultima', 'scorsa' => 'last',
            'prossima' => 'next',
            'questa' => 'this',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Italian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
