<?php

namespace Chrono\Locales\Vi\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Vi\CreatesParsedComponents;
use Chrono\Locales\Vi\ViConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class ViMonthYearParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Vietnamese month-year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = ViConstants::monthPattern();

        preg_match_all("/(?<![\\pL\\pN])(?<month>{$monthPattern})(?:\\s*(?:năm|\\/)\\s*(?<year>[0-9]{1,4}(?:\\s*TCN)?))?(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference): ?ParsedResult {
            $month = ViConstants::MONTHS[mb_strtolower($match['month'][0])] ?? null;

            if ($month === null) {
                return null;
            }

            if (preg_match('/ngày\s*[0-9]{1,2}\s*$/iu', substr($text, 0, $match[0][1])) === 1) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? ViConstants::year($yearText) : $reference->date->year;
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
            ])->addTag('parser/VIMonthYearParser'));
        }, $matches)));
    }
}
