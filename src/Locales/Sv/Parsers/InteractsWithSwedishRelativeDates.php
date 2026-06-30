<?php

namespace DirectoryTree\Chrono\Locales\Sv\Parsers;

use DirectoryTree\Chrono\Locales\Sv\SvConstants;
use DirectoryTree\Chrono\Pattern;

trait InteractsWithSwedishRelativeDates
{
    /**
     * @return array<string, int|float>
     */
    protected function duration(string $duration, bool $allowAbbreviations = true): array
    {
        $numberPattern = SvConstants::numberPattern();
        $unitPattern = $allowAbbreviations
            ? SvConstants::timeUnitPattern()
            : SvConstants::timeUnitNoAbbrPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,5}(?<unit>{$unitPattern})\\s{0,5}/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = $allowAbbreviations
                ? SvConstants::timeUnit($match['unit'])
                : SvConstants::timeUnitNoAbbr($match['unit']);

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + SvConstants::number($match['number']);
        }

        return $units;
    }

    /**
     * Get the parser pattern.
     */
    protected function durationPattern(bool $allowAbbreviations = true): string
    {
        $numberPattern = SvConstants::numberPattern();
        $unitPattern = $allowAbbreviations
            ? SvConstants::timeUnitPattern()
            : SvConstants::timeUnitNoAbbrPattern();
        $single = "(?:{$numberPattern})\\s{0,5}(?:{$unitPattern})\\s{0,5}";

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:och)|,)?\\s*');
    }
}
