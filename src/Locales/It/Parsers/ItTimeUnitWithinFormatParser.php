<?php

namespace DirectoryTree\Chrono\Locales\It\Parsers;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ItTimeUnitWithinFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithItalianRelativeDates;

    /**
     * Get the Italian within/in relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $prefix = $options->forwardDate() ? '(?:(?<prefix>within|in|for)\s*)?' : '(?<prefix>within|in|for)\s*';

        return "{$prefix}(?:(?:più o meno|intorno|approssimativamente|verso|verso le)\\s*(?:~\\s*)?)?".
            '(?<duration>'.$this->durationPattern().')(?=\W|$)';
    }

    /**
     * Extract relative date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedResult
    {
        $duration = $this->duration($match['duration'][0]);

        if ($duration === []) {
            return null;
        }

        $prefix = $match['prefix'][0] ?? '';
        $index = $prefix === '' ? $match['duration'][1] : $match[0][1];
        $text = $prefix === '' ? $match['duration'][0] : trim($match[0][0]);

        return new ParsedResult(
            $index,
            $text,
            ParsedComponents::createRelativeFromReference($reference, $duration),
        );
    }

    /**
     * Use a Unicode-safe left boundary for Italian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
