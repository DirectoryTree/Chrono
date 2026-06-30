<?php

namespace DirectoryTree\Chrono\Locales\Nl\Parsers;

use DirectoryTree\Chrono\Locales\Nl\NlConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class NlRelativeDateFormatParser implements Parser
{
    use InteractsWithDutchRelativeDates;

    /**
     * Parse Dutch relative date expressions such as "deze week".
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $unitPattern = NlConstants::timeUnitPattern();

        preg_match_all("/(?<![\\pL\\pN])(?<modifier>dit|deze|(?:aan)?komend|volgend|afgelopen|vorig)e?\\s*(?<unit>{$unitPattern})(?=\\s*)(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $modifier = mb_strtolower($match['modifier'][0]);
            $unitWord = mb_strtolower($match['unit'][0]);
            $unit = NlConstants::timeUnit($unitWord);

            if ($unit === null) {
                return null;
            }

            $date = $reference->date;

            if (in_array($modifier, ['volgend', 'komend', 'aankomend'], true)) {
                $date = $this->applyDuration($date, [$unit => 1], 1);

                return new ParsedResult($match[0][1], trim($match[0][0]), $this->relativeComponents(
                    $date,
                    $this->certainComponents($date, $unit),
                ), null, ['result/relativeDate']);
            }

            if (in_array($modifier, ['afgelopen', 'vorig'], true)) {
                $date = $this->applyDuration($date, [$unit => 1], -1);

                return new ParsedResult($match[0][1], trim($match[0][0]), $this->relativeComponents(
                    $date,
                    $this->certainComponents($date, $unit),
                ), null, ['result/relativeDate']);
            }

            $date = match ($unit) {
                'week' => $date->subDays($date->dayOfWeek),
                'month' => $date->day(1),
                'year' => $date->month(1)->day(1),
                default => $date,
            };

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->relativeComponents(
                $date,
                $this->certainComponents($date, $unit),
            ), null, ['result/relativeDate']);
        }, $matches)));
    }
}
