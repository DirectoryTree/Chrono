<?php

namespace Chrono\Locales\En\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class EnTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithRelativeDates;

    /**
     * Create an English casual relative time-unit parser.
     */
    public function __construct(
        /**
         * Whether abbreviated time units are accepted.
         */
        protected readonly bool $allowAbbreviations = true,
    ) {}

    /**
     * Get the English casual relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $unit = $this->allowAbbreviations
            ? '(?:seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|weeks?|w|months?|mons?|mos?|mo|quarters?|qtrs?|qtr|years?|yrs?|y)'
            : '(?:seconds?|minutes?|hours?|days?|weeks?|months?|quarters?|years?)';

        $amount = '(?:(?:\d+(?:\.\d+)?\s*)|(?:(?:a\s+few|a\s+couple\s+of|several|an?|the|one|two|three|four|five|six|seven|eight|nine|ten|few|half\s+an?)\s+))';

        return '(?<modifier>this|last|past|next|after|[+-])\s*'.
            '(?<duration>'.$amount.$unit.'(?:\s*(?:,?\s*and|,)?\s*'.$amount.$unit.')*)'.
            '(?:\s+(?<hour>\d{1,2})(?::(?<minute>\d{2}))?)?'.
            '(?=\W|$)';
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

        $modifier = strtolower($match['modifier'][0]);

        if (in_array($modifier, ['last', 'past', '-'], true)) {
            $duration = Duration::reverse($duration);
        }

        $components = ParsedComponents::createRelativeFromReference($reference, $duration);

        if (($match['hour'][0] ?? '') !== '') {
            $components
                ->imply('hour', (int) $match['hour'][0])
                ->imply('minute', ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0);
        }

        return $components;
    }
}
