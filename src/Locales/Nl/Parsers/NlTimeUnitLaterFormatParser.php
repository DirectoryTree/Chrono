<?php

namespace DirectoryTree\Chrono\Locales\Nl\Parsers;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class NlTimeUnitLaterFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithDutchRelativeDates;

    /**
     * Create a parser instance.
     */
    public function __construct(
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the Dutch future relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $suffix = $this->strictMode ? 'later|vanaf nu' : 'later|na|vanaf nu|voortaan|vooruit|uit';

        return '(?<duration>'.$this->durationPattern().")(?:{$suffix})(?=(?:\\W|$))";
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
