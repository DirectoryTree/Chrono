<?php

namespace Chrono\Locales\Fr\Parsers;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FrTimeUnitWithinFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithFrenchRelativeDates;

    /**
     * Get the French within/in relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:dans|en|pour|pendant|de)\s*(?<duration>'.$this->durationPattern().')(?=\W|$)';
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

        return ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/FRTimeUnitWithinFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for French words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
