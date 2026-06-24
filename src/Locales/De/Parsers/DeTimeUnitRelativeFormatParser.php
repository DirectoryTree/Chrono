<?php

namespace Chrono\Locales\De\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class DeTimeUnitRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithGermanRelativeDates;

    /**
     * Get the German modifier-relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $numberPattern = $this->numberPattern();
        $unitPattern = $this->timeUnitPattern();
        $modifier = '(?:nächste|kommende|folgende|letzte|vergangene|vorige|vor(?:her|an)gegangene)(?:s|n|m|r)?|vor|in';

        return "(?:(?<prefix>{$modifier})\\s*)?".
            '(?:(?:den|die|der|dem)\s*)?'.
            "(?<number>{$numberPattern})?".
            "(?:\\s*(?<postmodifier>{$modifier}))?".
            "\\s*(?<unit>{$unitPattern})(?=\\W|$)";
    }

    /**
     * Extract relative date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedResult
    {
        $modifier = $this->normalize(($match['postmodifier'][0] ?? '') ?: ($match['prefix'][0] ?? ''));

        if ($modifier === '') {
            return null;
        }

        $unit = $this->timeUnit($match['unit'][0]);

        if ($unit === null) {
            return null;
        }

        $duration = [$unit => (($match['number'][0] ?? '') !== '') ? $this->number($match['number'][0]) : 1];

        if (preg_match('/vor|letzt|vergangen/iu', $modifier) === 1) {
            $duration = Duration::reverse($duration);
        }

        $index = (($match['postmodifier'][0] ?? '') !== '' && ($match['number'][0] ?? '') !== '')
            ? $match['number'][1]
            : $match[0][1];
        $text = substr($match[0][0], $index - $match[0][1]);
        $components = ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/DETimeUnitRelativeFormatParser');

        return new ParsedResult($index, trim($text), $components);
    }

    /**
     * Use a Unicode-safe left boundary for German words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
