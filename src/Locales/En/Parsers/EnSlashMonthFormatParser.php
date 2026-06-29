<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class EnSlashMonthFormatParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/\b(?<month>[0-9]|0[1-9]|1[012])\/(?<year>\d{4})\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = (int) $match['month'][0];
            $year = (int) $match['year'][0];
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
                'year' => $year,
                'month' => $month,
            ]));
        }, $matches)));
    }
}
