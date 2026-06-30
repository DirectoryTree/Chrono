<?php

namespace DirectoryTree\Chrono\Locales\Fr\Parsers;

use DirectoryTree\Chrono\Calculation\Duration;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class FrTimeUnitRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithFrenchRelativeDates;

    /**
     * Get the French modifier-relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $numberPattern = $this->numberPattern();
        $unitPattern = $this->timeUnitPattern();
        $modifier = 'prochaine?s?|derni[eè]re?s?|pass[ée]e?s?|pr[ée]c[ée]dents?|suivante?s?';

        return "(?:les?|la|l'|l’|du|des?)\\s*".
            "(?<number>{$numberPattern})?".
            "(?:\\s*(?<leadingModifier>{$modifier}))?".
            "\\s*(?<unit>{$unitPattern})".
            "(?:\\s*(?<trailingModifier>{$modifier}))?";
    }

    /**
     * Extract relative date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $modifier = $match['leadingModifier'][0] ?: ($match['trailingModifier'][0] ?? '');

        if ($modifier === '') {
            return null;
        }

        $unit = $this->timeUnit($match['unit'][0]);

        if ($unit === null) {
            return null;
        }

        $duration = [$unit => (($match['number'][0] ?? '') !== '') ? $this->number($match['number'][0]) : 1];

        if ($this->isPastModifier($modifier)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/FRTimeUnitRelativeFormatParser');
    }

    /**
     * Determine whether the modifier points to the past.
     */
    protected function isPastModifier(string $modifier): bool
    {
        return preg_match('/derni[eè]re?s?|pass[ée]e?s?|pr[ée]c[ée]dents?/iu', $modifier) === 1;
    }

    /**
     * Use a Unicode-safe left boundary for French words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
