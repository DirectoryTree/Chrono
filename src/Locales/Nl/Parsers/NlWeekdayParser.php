<?php

namespace DirectoryTree\Chrono\Locales\Nl\Parsers;

use DirectoryTree\Chrono\Calculation\Weekdays;
use DirectoryTree\Chrono\Locales\Nl\NlConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class NlWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Dutch weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = NlConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:op\\s*?)?(?:(?<modifier>deze|vorige|volgende)\\s*(?:week\\s*)?)?(?<weekday>{$weekdayPattern})(?=\\W|$)";
    }

    /**
     * Extract Dutch weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = NlConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier($match['modifier'][0] ?? '')
        );

        $components
            ->imply('hour', 12)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('parser/NLWeekdayParser');

        return $components;
    }

    /**
     * Normalize Dutch weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'vorige' => 'last',
            'volgende' => 'next',
            'deze' => 'this',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Dutch words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
