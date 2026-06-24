<?php

namespace Chrono\Locales\Nl\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class NlTimeUnitAgoFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithDutchRelativeDates;

    /**
     * Create a parser instance.
     */
    public function __construct(
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the Dutch past relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $suffix = $this->strictMode ? 'geleden' : 'geleden|voor|eerder';

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

        return ParsedComponents::createRelativeFromReference($reference, Duration::reverse($duration));
    }

    /**
     * Use a Unicode-safe left boundary for Dutch words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
