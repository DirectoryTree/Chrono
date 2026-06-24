<?php

namespace Chrono\Locales\It\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\It\CreatesParsedComponents;
use Chrono\Locales\It\ItConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class ItMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Italian month-only and month-year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = ItConstants::monthPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?<prefix>in\\s*)?(?<month>{$monthPattern})\\s*(?:[,-]?\\s*(?<year>[1-2][0-9]{3}|[5-9][0-9]))?(?=[^\\s\\w]|\\s+[^0-9]|\\s+$|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $monthText = mb_strtolower($match['month'][0]);

            if (strlen($match[0][0]) <= 3 && strlen($monthText) <= 3) {
                return null;
            }

            $month = ItConstants::monthNumber($monthText);

            if ($month === null) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? Years::findMostLikelyADYear((int) $yearText) : Years::findYearClosestToReference($reference->date, 1, $month);
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);
            $prefixLength = strlen($match['prefix'][0] ?? '');
            $text = substr($match[0][0], $prefixLength);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
            ]);
            $components->addTag('parser/ITMonthNameParser');

            return new ParsedResult($match[0][1] + $prefixLength, $text, $components);
        }, $matches)));
    }
}
