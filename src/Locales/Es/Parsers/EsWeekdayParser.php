<?php

namespace Chrono\Locales\Es\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Es\EsConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class EsWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Spanish weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = EsConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:(?<modifier>este|esta|pasado|pr[oó]ximo)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?<postmodifier>este|esta|pasado|pr[oó]ximo)\\s*semana)?(?=\\W|\\d|$)";
    }

    /**
     * Extract Spanish weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = EsConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        $modifier = $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''));

        if ($modifier === null && $options->forwardDate()) {
            $modifier = 'next';
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $modifier
        );

        $components
            ->imply('hour', 0)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag('parser/ESWeekdayParser');

        return $components;
    }

    /**
     * Normalize Spanish weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'pasado', 'este', 'esta' => 'this',
            'próximo', 'proximo' => 'next',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Spanish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
