<?php

namespace Chrono\Locales\Vi\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Vi\CreatesParsedComponents;
use Chrono\Locales\Vi\ViConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class ViStandardParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Vietnamese day-month-year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\pL\pN])(?:ngày\s*)?(?<day>[0-9]{1,2})\s*tháng\s*(?<month>[0-9]{1,2})(?:\s*năm\s*(?<year>[0-9]{1,4}(?:\s*TCN)?))?(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $day = (int) $match['day'][0];
            $month = (int) $match['month'][0];

            if ($day > 31 || $month > 12) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? ViConstants::year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if ($year > 0 && ! checkdate($month, $day, $year)) {
                return null;
            }

            if ($year < 1 && ($month < 1 || $month > 12 || $day < 1 || $day > 31)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ])->addTag('parser/VIStandardParser'));
        }, $matches)));
    }
}
