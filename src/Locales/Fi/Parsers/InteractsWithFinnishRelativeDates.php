<?php

namespace Chrono\Locales\Fi\Parsers;

use Chrono\Locales\Fi\FiConstants;
use Chrono\Pattern;

trait InteractsWithFinnishRelativeDates
{
    /**
     * Parse a Finnish time-unit phrase into duration fragments.
     *
     * @return array<string, int>
     */
    protected function duration(string $duration): array
    {
        $numberPattern = FiConstants::numberPattern();
        $unitPattern = FiConstants::timeUnitPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,5}(?<unit>{$unitPattern})\\s{0,5}/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = FiConstants::timeUnit($match['unit']);

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + FiConstants::number($match['number']);
        }

        return $units;
    }

    /**
     * Build a regex pattern for one or more Finnish time-unit fragments.
     */
    protected function durationPattern(bool $allowAbbreviations = true): string
    {
        $numberPattern = FiConstants::numberPattern();
        $unitPattern = FiConstants::timeUnitPattern($allowAbbreviations);
        $single = "(?:{$numberPattern})\\s{0,5}(?:{$unitPattern})\\s{0,5}";

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:ja)|,)?\\s*');
    }
}
