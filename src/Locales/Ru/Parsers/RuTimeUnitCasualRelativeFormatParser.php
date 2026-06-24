<?php

namespace Chrono\Locales\Ru\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class RuTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithRussianRelativeDates;

    /**
     * Get the Russian casual relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<modifier>эти|последние|прошлые|следующие|после|спустя|через|\+|-)\s*'.
            '(?<duration>'.$this->durationPattern().')(?=\W|$)';
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

        if ($this->isPastModifier($match['modifier'][0])) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/RUTimeUnitCasualRelativeFormatParser');
    }

    /**
     * Determine whether a Russian casual duration modifier points to the past.
     */
    protected function isPastModifier(string $modifier): bool
    {
        return in_array(mb_strtolower($modifier), ['последние', 'прошлые', '-'], true);
    }

    /**
     * Use a Unicode-safe left boundary for Russian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
