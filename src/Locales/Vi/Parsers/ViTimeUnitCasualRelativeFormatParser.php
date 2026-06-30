<?php

namespace DirectoryTree\Chrono\Locales\Vi\Parsers;

use DirectoryTree\Chrono\Calculation\Duration;
use DirectoryTree\Chrono\Locales\Vi\ViConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ViTimeUnitCasualRelativeFormatParser extends AbstractParserWithWordBoundary
{
    use InteractsWithVietnameseRelativeDates;

    /**
     * Get the Vietnamese casual relative time-unit parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        $numberPattern = ViConstants::numberPattern();
        $unitPattern = ViConstants::timeUnitPattern();
        $casualUnit = "(?:(?:{$numberPattern})\\s{0,5})?(?:{$unitPattern})";

        return "(?:(?<prefix>này|trước|qua|sau|tới|tiếp)\\s*(?<prefixunit>{$casualUnit})|".
            "(?<suffixunit>{$casualUnit})\\s*(?<suffix>này|trước|qua|sau|tới|tiếp))(?=\\W|$)";
    }

    /**
     * Extract relative date components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $modifier = mb_strtolower(($match['prefix'][0] ?? '') ?: ($match['suffix'][0] ?? ''));
        $unitText = mb_strtolower(($match['prefixunit'][0] ?? '') ?: ($match['suffixunit'][0] ?? ''));
        $duration = $this->duration($unitText);

        if ($duration === []) {
            $unit = ViConstants::TIME_UNITS[$unitText] ?? null;

            if ($unit === null) {
                return null;
            }

            $duration = [$unit => 1];
        }

        if (in_array($modifier, ['trước', 'qua'], true)) {
            $duration = Duration::reverse($duration);
        }

        return ParsedComponents::createRelativeFromReference($reference, $duration)
            ->addTag('parser/VITimeUnitCasualRelativeFormatParser');
    }

    /**
     * Use a Unicode-safe left boundary for Vietnamese words.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
