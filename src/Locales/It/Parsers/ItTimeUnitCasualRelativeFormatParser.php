<?php

namespace Chrono\Locales\It\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class ItTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithItalianRelativeDates;

    /**
     * Get the casual Italian relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<modifier>questo|ultimo|passato|prossimo|dopo|questa|ultima|passata|prossima|\+|-)\s*'.
            '(?<duration>'.$this->durationPattern().')'.
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

        $modifier = mb_strtolower($match['modifier'][0]);

        if (in_array($modifier, ['ultimo', 'ultima', 'passato', 'passata', '-'], true)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration);
    }

    /**
     * Use a Unicode-safe left boundary for Italian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
