<?php

namespace Chrono\Locales\Ru\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Ru\RuConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class RuWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Russian weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = RuConstants::weekdayPattern();

        return "(?:(?:,|\\(|（)\\s*)?(?:в\\s*?)?(?:(?<modifier>эту|этот|прошлый|прошлую|следующий|следующую|следующего)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*на\\s*(?<postmodifier>этой|прошлой|следующей)\\s*неделе)?(?=\\W|$)";
    }

    /**
     * Extract Russian weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = RuConstants::WEEKDAYS[mb_strtolower($match['weekday'][0])] ?? null;

        if ($weekday === null) {
            return null;
        }

        $modifier = $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''));

        if ($modifier === null && $options->forwardDate()) {
            $modifier = 'next';
        }

        return Weekdays::createParsingComponentsAtWeekday($reference, $weekday, $modifier)
            ->imply('hour', 0)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('parser/RUWeekdayParser');
    }

    /**
     * Normalize Russian weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'прошлый', 'прошлую', 'прошлой' => 'last',
            'следующий', 'следующую', 'следующей', 'следующего' => 'next',
            'этот', 'эту', 'этой' => 'this',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Russian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
