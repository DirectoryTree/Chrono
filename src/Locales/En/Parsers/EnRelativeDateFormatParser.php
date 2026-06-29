<?php

namespace Chrono\Locales\En\Parsers;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class EnRelativeDateFormatParser implements Parser
{
    use InteractsWithRelativeDates;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/\b(?<modifier>this|last|past|next|after\s+this)\s*(?<amount>one|two|three|four|five|six|seven|eight|nine|ten|\d+)?\s*(?<unit>seconds?|minutes?|hours?|days?|weeks?|months?|quarters?|qtrs?|years?)\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_map(function (array $match) use ($reference): ParsedResult {
            $modifier = strtolower($match['modifier'][0]);
            $unit = $this->unit($match['unit'][0]);
            $amount = (($match['amount'][0] ?? '') !== '') ? $this->amount($match['amount'][0]) : 1;
            $date = $reference->date;

            if ($modifier === 'this') {
                $date = match ($unit) {
                    'week' => $date,
                    'month' => $date->day(1),
                    'year' => $date->month(1)->day(1),
                    default => $date,
                };
            } else {
                $direction = in_array($modifier, ['last', 'past'], true) ? -1 : 1;
                $date = $this->applyDuration($date, [$unit => $amount], $direction);
            }

            return new ParsedResult($match[0][1], $match[0][0], $this->relativeComponents($date, $this->certainComponents($date, $unit)), null, ['result/relativeDate']);
        }, $matches);
    }
}
