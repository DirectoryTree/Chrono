<?php

namespace Chrono\Locales\Pt\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Pt\PtConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class PtWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Portuguese weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = PtConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:(?<modifier>este|esta|passado|pr[oó]ximo)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?<postmodifier>este|esta|passado|pr[oó]ximo)\\s*semana)?(?=\\W|\\d|$)";
    }

    /**
     * Extract Portuguese weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = PtConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''))
        );

        $components
            ->imply('hour', 0)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('parser/PTWeekdayParser');

        return $components;
    }

    /**
     * Normalize Portuguese weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'passado', 'este', 'esta' => 'this',
            'próximo', 'proximo' => 'next',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Portuguese words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
