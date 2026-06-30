<?php

namespace DirectoryTree\Chrono\Locales\Nl\Parsers;

use DirectoryTree\Chrono\Calculation\Duration;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class NlTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithDutchRelativeDates;

    /**
     * Get the casual Dutch relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<modifier>dit|deze|vorig|afgelopen|(?:aan)?komend|over|\+|-)e?\s*'.
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

        if (in_array($modifier, ['vorig', 'afgelopen', '-'], true)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration);
    }

    /**
     * Use a Unicode-safe left boundary for Dutch words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
