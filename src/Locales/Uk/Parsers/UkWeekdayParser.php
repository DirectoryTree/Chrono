<?php

namespace Chrono\Locales\Uk\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Uk\UkConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class UkWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Ukrainian weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = UkConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:в\\s*?)?(?:у\\s*?)?(?:(?<modifier>цей|минулого|минулий|попередній|попереднього|наступного|наступний|наступному)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?:на|у|в)\\s*(?<postmodifier>цьому|минулому|наступному)\\s*тижні)?(?=\\W|$)";
    }

    /**
     * Extract Ukrainian weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = UkConstants::WEEKDAYS[mb_strtolower($match['weekday'][0])] ?? null;

        if ($weekday === null) {
            return null;
        }

        $modifier = $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''));

        if ($modifier === null && $options->forwardDate()) {
            $modifier = 'next';
        }

        return Weekdays::createParsingComponentsAtWeekday($reference, $weekday, $modifier)
            ->imply('hour', 12)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('parser/UKWeekdayParser');
    }

    /**
     * Normalize Ukrainian weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'минулого', 'минулий', 'попередній', 'попереднього', 'минулому' => 'last',
            'наступного', 'наступний', 'наступному' => 'next',
            'цей', 'цього', 'цьому' => 'this',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Ukrainian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
