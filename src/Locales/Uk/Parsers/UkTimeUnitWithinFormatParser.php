<?php

namespace DirectoryTree\Chrono\Locales\Uk\Parsers;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class UkTimeUnitWithinFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithUkrainianRelativeDates;

    /**
     * Get the Ukrainian within/in relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $prefix = $options->forwardDate()
            ? ''
            : '(?:протягом|на\s+протязі|упродовж|впродовж)\s*';

        return "{$prefix}(?:(?:приблизно|орієнтовно)\\s*(?:~\\s*)?)?(?<duration>".$this->durationPattern().')(?=\W|$)';
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
            ->addTag('parser/UKTimeUnitWithinFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Ukrainian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
