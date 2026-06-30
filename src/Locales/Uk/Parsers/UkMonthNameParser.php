<?php

namespace DirectoryTree\Chrono\Locales\Uk\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;
use DirectoryTree\Chrono\Locales\Uk\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\Uk\UkConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class UkMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Ukrainian month and optional year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = UkConstants::monthPattern();
        $yearPattern = '[1-9][0-9]{0,3}(?:\s+(?:року|рік|р\.?))?\s*(?:н\.?\s*е\.?|до\s+н\.?\s*е\.?)|[1-2][0-9]{3}(?:\s+(?:року|рік|р\.?))?|[5-9][0-9](?:\s+(?:року|рік|р\.?))?';

        preg_match_all("/(?<![\\pL\\pN])(?:(?:в|у)\\s*)?(?<month>{$monthPattern})\\s*(?:[,-]?\\s*(?<year>{$yearPattern})?)?(?=[^\\s\\w]|\\s+[^0-9]|\\s+$|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $monthName = mb_strtolower($match['month'][0]);

            if (mb_strlen($match[0][0]) <= 3 && ! array_key_exists($monthName, UkConstants::FULL_MONTHS)) {
                return null;
            }

            $month = UkConstants::MONTHS[$monthName] ?? null;

            if ($month === null) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? UkConstants::year($yearText) : Years::findYearClosestToReference($reference->date, 1, $month);
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => 1,
            ])->addTag('parser/UKMonthNameParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components);
        }, $matches)));
    }
}
