<?php

namespace Chrono\Locales\Fi\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Fi\FiConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FiWeekdayParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the Finnish weekday pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $weekdayPattern = FiConstants::weekdayPattern();

        return "(?:[,\\(（]\\s*)?(?:(?<modifier>viime|edellinen|edellisenä|ensi|seuraava|seuraavana|tämä|tänä)\\s*)?(?<weekday>{$weekdayPattern})(?:\\s*(?:,|\\)|）))?(?:\\s*(?<postmodifier>viime|ensi|seuraava)\\s*viikolla)?(?=\\W|$)";
    }

    /**
     * Extract Finnish weekday components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $weekday = FiConstants::weekdayNumber($match['weekday'][0]);

        if ($weekday === null) {
            return null;
        }

        $components = Weekdays::createParsingComponentsAtWeekday(
            $reference,
            $weekday,
            $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''))
        );

        $components->addTag('parser/FIWeekdayParser');

        return $components;
    }

    /**
     * Normalize Finnish weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (true) {
            preg_match('/viime|edellinen|edellisenä/iu', $modifier) === 1 => 'last',
            preg_match('/ensi|seuraava|seuraavana/iu', $modifier) === 1 => 'next',
            default => null,
        };
    }

    /**
     * Use a Unicode-safe left boundary for Finnish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
