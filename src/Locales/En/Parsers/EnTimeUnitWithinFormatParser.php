<?php

namespace Chrono\Locales\En\Parsers;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class EnTimeUnitWithinFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithRelativeDates;

    /**
     * Create an English within-duration parser.
     */
    public function __construct(
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the English within/in relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $prefix = $options->forwardDate() && ! $this->strictMode
            ? '(?:(?<prefix>within|in|for)\s*)?'
            : '(?<prefix>within|in|for)\s*';
        $duration = $this->durationPattern(allowAbbreviations: ! $this->strictMode);

        return $prefix.
            '(?:(?:about|around|roughly|approximately|just)\s*(?:~\s*)?)?'.
            "(?<duration>{$duration})(?=\\W|$)";
    }

    /**
     * Extract relative date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedResult
    {
        if (preg_match('/^for\s*the\s*\w+/i', $match[0][0]) === 1) {
            return null;
        }

        $duration = $this->duration($match['duration'][0]);

        if ($duration === []) {
            return null;
        }

        $prefix = $match['prefix'][0] ?? '';
        $index = $prefix === '' ? $match['duration'][1] : $match[0][1];
        $text = $prefix === '' ? $match['duration'][0] : trim($match[0][0]);

        return new ParsedResult($index, $text, ParsedComponents::createRelativeFromReference($reference, $duration));
    }

    /**
     * Get the English relative duration parser pattern.
     */
    protected function durationPattern(bool $allowAbbreviations = true): string
    {
        $unit = $allowAbbreviations
            ? '(?:seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|weeks?|w|months?|mons?|mos?|mo|quarters?|qtrs?|qtr|years?|yrs?|y)'
            : '(?:seconds?|minutes?|hours?|days?|weeks?|months?|quarters?|years?)';
        $amount = '(?:(?:\d+(?:\.\d+)?\s*)|(?:(?:a\s+few|a\s+couple\s+of|several|an?|the|one|two|three|four|five|six|seven|eight|nine|ten|few|half\s+an?)\s+))';

        return $amount.$unit.'(?:\s*(?:,?\s*and|,)?\s*'.$amount.$unit.')*';
    }

    /**
     * Use a zero-width word boundary like upstream's parser wrapper.
     */
    protected function patternLeftBoundary(): string
    {
        return '(\b)';
    }
}
