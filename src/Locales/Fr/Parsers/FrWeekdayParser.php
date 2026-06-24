<?php

namespace Chrono\Locales\Fr\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Fr\FrConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FrWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the French weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = FrConstants::weekdayPattern();

        return "(?:(?:,|\\(|（)\\s*)?(?:(?:ce)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?<postfix>dernier|prochain)\\s*)?(?=\\W|\\d|$)";
    }

    /**
     * Extract French weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = FrConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        return Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier($match['postfix'][0] ?? '')
        )
            ->imply('hour', 12)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('parser/FRWeekdayParser');
    }

    /**
     * Normalize French weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'dernier' => 'last',
            'prochain' => 'next',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for French words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
