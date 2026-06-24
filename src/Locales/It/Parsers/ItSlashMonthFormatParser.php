<?php

namespace Chrono\Locales\It\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\It\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class ItSlashMonthFormatParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Italian numeric month/year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\d\/.-])(?<month>[0-9]|0[1-9]|1[012])\/(?<year>[0-9]{4})(?![\d\/.-])/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_map(function (array $match) use ($reference): ParsedResult {
            $month = (int) $match['month'][0];
            $year = (int) $match['year'][0];
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            $components = $this->components($date, [
                'year' => $year,
                'month' => $month,
            ]);
            $components->addTag('parser/ITSlashMonthFormatParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches);
    }
}
