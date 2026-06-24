<?php

namespace Chrono\Locales\It\Parsers;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class ItTimeUnitLaterFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithItalianRelativeDates;

    /**
     * Create a parser instance.
     */
    public function __construct(
        /**
         * Whether strict relative-duration parsing is enabled.
         */
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Get the Italian future relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $suffix = $this->strictMode ? 'dopo|più tardi' : 'dopo|più tardi|da adesso|avanti|oltre|a seguire';

        return '(?<duration>'.$this->durationPattern().")\\s{0,5}(?:{$suffix})(?=\\W|$)";
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
     * Use a Unicode-safe left boundary for Italian words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
