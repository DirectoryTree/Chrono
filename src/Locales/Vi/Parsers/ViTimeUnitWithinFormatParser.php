<?php

namespace Chrono\Locales\Vi\Parsers;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class ViTimeUnitWithinFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithVietnameseRelativeDates;

    /**
     * Create a parser instance.
     */
    public function __construct(
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the Vietnamese within/in relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return 'trong\s*(?:vòng\s*)?(?<duration>'.$this->durationPattern().')(?=\W|$)';
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
            ->addTag('parser/VITimeUnitWithinFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Vietnamese words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
