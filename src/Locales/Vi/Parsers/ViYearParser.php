<?php

namespace Chrono\Locales\Vi\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Vi\CreatesParsedComponents;
use Chrono\Locales\Vi\ViConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class ViYearParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Vietnamese year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\pL\pN])(?:năm\s*(?<year>[0-9]{1,4}(?:\s*TCN)?)|(?<bare>[0-9]{1,4})\s*(?<bc>TCN))(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference): ?ParsedResult {
            if (preg_match('/ngày\s*[0-9]{1,2}\s*tháng\s*[0-9]{1,2}\s*$/iu', substr($text, 0, $match[0][1])) === 1) {
                return null;
            }

            $yearText = ($match['year'][0] ?? '') ?: (($match['bare'][0] ?? '').' '.($match['bc'][0] ?? ''));
            $year = ViConstants::year($yearText);

            $date = CarbonImmutable::create($year, 1, 1, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, [
                'year' => $year,
            ])->addTag('parser/VIYearParser'));
        }, $matches)));
    }
}
