<?php

namespace Chrono\Locales\Vi\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Vi\ViConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class ViWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Vietnamese weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = ViConstants::weekdayPattern();

        return "(?<weekday>{$weekdayPattern})(?:\\s*(?<modifier>này|tới|sau(?!\\s*khi)|qua))?(?=\\W|$)";
    }

    /**
     * Extract Vietnamese weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = ViConstants::WEEKDAYS[mb_strtolower($match['weekday'][0])] ?? null;

        if ($weekday === null) {
            return null;
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier($match['modifier'][0] ?? '')
        );

        $components->addTag('parser/VIWeekdayParser');

        return $components;
    }

    /**
     * Normalize Vietnamese weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (true) {
            preg_match('/tới|sau/iu', $modifier) === 1 => 'next',
            preg_match('/qua/iu', $modifier) === 1 => 'last',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Vietnamese words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
