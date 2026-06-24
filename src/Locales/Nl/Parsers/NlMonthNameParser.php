<?php

namespace Chrono\Locales\Nl\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Nl\CreatesParsedComponents;
use Chrono\Locales\Nl\NlConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class NlMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Dutch month-only and month-year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = NlConstants::monthPattern();
        $yearPattern = NlConstants::yearPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?<month>{$monthPattern})\\s*(?:[,-]?\\s*(?<year>{$yearPattern}))?(?=[^\\s\\w]|\\s+[^0-9]|\\s+$|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = NlConstants::monthNumber($match['month'][0]);

            if ($month === null) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? NlConstants::year($yearText) : Years::findYearClosestToReference($reference->date, 1, $month);
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
            ]);
            $components->addTag('parser/NLMonthNameParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }
}
