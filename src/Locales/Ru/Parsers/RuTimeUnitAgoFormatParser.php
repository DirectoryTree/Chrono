<?php

namespace Chrono\Locales\Ru\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class RuTimeUnitAgoFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithRussianRelativeDates;

    /**
     * Get the Russian past relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<duration>'.$this->durationPattern().')\s{0,5}назад(?=\W|$)';
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

        return ParsedComponents::createRelativeFromReference($reference, Duration::reverse($duration))
            ->addTag('parser/RUTimeUnitAgoFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Russian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
