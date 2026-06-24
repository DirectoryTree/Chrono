<?php

namespace Chrono\Locales\Fi\Parsers;

use Chrono\Calculation\Duration;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FiTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithFinnishRelativeDates;

    /**
     * Create a Finnish casual relative time-unit parser.
     */
    public function __construct(
        protected readonly bool $allowAbbreviations = true,
    ) {}

    /**
     * Get the casual Finnish relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<modifier>seuraava|seuraavat|seuraavien|edellinen|edelliset|edellisten|viimeiset|viimeisten|kuluneet|kuluneiden|\+|-)\s*'.
            '(?<duration>'.$this->durationPattern($this->allowAbbreviations).')'.
            '(?=\W|$)';
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

        $modifier = mb_strtolower($match['modifier'][0]);

        if (in_array($modifier, ['edellinen', 'edelliset', 'edellisten', 'viimeiset', 'viimeisten', 'kuluneet', 'kuluneiden', '-'], true)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration);
    }

    /**
     * Use a Unicode-safe left boundary for Finnish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
