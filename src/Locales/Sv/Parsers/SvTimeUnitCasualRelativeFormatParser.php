<?php

namespace Chrono\Locales\Sv\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class SvTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithSwedishRelativeDates;

    /**
     * Get the casual Swedish relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<modifier>denna|den här|förra|passerade|nästa|kommande|efter|\+|-)\s*'.
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

        if (in_array($modifier, ['förra', 'passerade', '-'], true)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/SVTimeUnitCasualRelativeFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Swedish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
