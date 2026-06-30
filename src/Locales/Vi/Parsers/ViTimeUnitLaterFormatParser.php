<?php

namespace DirectoryTree\Chrono\Locales\Vi\Parsers;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ViTimeUnitLaterFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithVietnameseRelativeDates;

    /**
     * Create a parser instance.
     */
    public function __construct(
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the Vietnamese future relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<duration>'.$this->durationPattern().')\s{0,5}(?:sau|nữa|tới|tiếp)(?=\W|$)';
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
            ->addTag('parser/VITimeUnitLaterFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Vietnamese words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
