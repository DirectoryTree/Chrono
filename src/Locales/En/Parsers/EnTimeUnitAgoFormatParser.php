<?php

namespace Chrono\Locales\En\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class EnTimeUnitAgoFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithRelativeDates;

    /**
     * Create an English past relative-duration parser.
     */
    public function __construct(
        /**
         * Whether strict relative-duration parsing is enabled.
         */
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the English past relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $duration = $this->durationPattern(allowAbbreviations: ! $this->strictMode);

        return "(?<duration>{$duration})\\s{0,5}(?:ago|before|earlier)(?=\\W|$)";
    }

    /**
     * Extract relative date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $duration = $this->duration($match['duration'][0]);

        if ($duration === []) {
            return null;
        }

        return ParsedComponents::createRelativeFromReference($reference, Duration::reverse($duration));
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
