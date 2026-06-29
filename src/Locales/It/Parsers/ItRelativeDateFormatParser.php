<?php

namespace Chrono\Locales\It\Parsers;

use Chrono\Locales\It\ItConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class ItRelativeDateFormatParser implements Parser
{
    use InteractsWithItalianRelativeDates;

    /**
     * Parse Italian relative date expressions such as "questa settimana".
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $unitPattern = ItConstants::timeUnitPattern();

        preg_match_all("/(?<![\\pL\\pN])(?<modifier>questo|ultimo|scorso|prossimo|dopo\\s*questo|questa|ultima|scorsa|prossima\\s*questa)\\s*(?<unit>{$unitPattern})(?=\\s*)(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $modifier = mb_strtolower($match['modifier'][0]);
            $unitWord = mb_strtolower($match['unit'][0]);
            $unit = ItConstants::timeUnit($unitWord);

            if ($unit === null) {
                return null;
            }

            $date = $reference->date;

            if ($modifier === 'prossimo' || str_starts_with($modifier, 'dopo')) {
                $date = $this->applyDuration($date, [$unit => 1], 1);

                return new ParsedResult($match[0][1], trim($match[0][0]), $this->relativeComponents(
                    $date,
                    $this->certainComponents($date, $unit),
                ), null, ['result/relativeDate']);
            }

            if ($modifier === 'prima' || $modifier === 'precedente') {
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
