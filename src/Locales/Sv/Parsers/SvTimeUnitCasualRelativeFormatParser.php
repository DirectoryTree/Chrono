<?php

namespace DirectoryTree\Chrono\Locales\Sv\Parsers;

use DirectoryTree\Chrono\Calculation\Duration;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class SvTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithSwedishRelativeDates;

    /**
     * Create a Swedish casual relative time-unit parser.
     */
    public function __construct(
        protected readonly bool $allowAbbreviations = true,
    ) {}

    /**
     * Get the casual Swedish relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<modifier>denna|den här|förra|passerade|nästa|kommande|efter|\+|-)\s*'.
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
        $duration = $this->duration($match['duration'][0], $this->allowAbbreviations);

        if ($duration === []) {
            return null;
        }

        $modifier = mb_strtolower($match['modifier'][0]);

        if (in_array($modifier, ['förra', 'passerade', '-'], true)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/SVTimeUnitCasualRelativeFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Swedish words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
