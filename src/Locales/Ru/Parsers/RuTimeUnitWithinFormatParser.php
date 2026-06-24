<?php

namespace Chrono\Locales\Ru\Parsers;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class RuTimeUnitWithinFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithRussianRelativeDates;

    /**
     * Get the Russian within/in relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $prefix = $options->forwardDate() ? '' : '(?:в течение|в течении)\s*';

        return "{$prefix}(?:(?:около|примерно)\\s*(?:~\\s*)?)?(?<duration>".$this->durationPattern().')(?=\W|$)';
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
            ->addTag('parser/RUTimeUnitWithinFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Russian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
