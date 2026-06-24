<?php

namespace Chrono\Locales\Sv\Parsers;

use Chrono\Locales\Sv\SvConstants;
use Chrono\Pattern;

trait InteractsWithSwedishRelativeDates
{
    /**
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        $numberPattern = SvConstants::numberPattern();
        $unitPattern = SvConstants::timeUnitPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,5}(?<unit>{$unitPattern})\\s{0,5}/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = SvConstants::timeUnit($match['unit']);

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + SvConstants::number($match['number']);
        }

        return $units;
    }

    protected function durationPattern(): string
    {
        $numberPattern = SvConstants::numberPattern();
        $unitPattern = SvConstants::timeUnitPattern();
        $single = "(?:{$numberPattern})\\s{0,5}(?:{$unitPattern})\\s{0,5}";

        return Pattern::repeatedTimeunitPattern('', $single, '\\s*(?:,?\\s*(?:och)|,)?\\s*');
    }

}
