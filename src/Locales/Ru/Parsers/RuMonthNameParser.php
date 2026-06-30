<?php

namespace DirectoryTree\Chrono\Locales\Ru\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;
use DirectoryTree\Chrono\Locales\Ru\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\Ru\RuConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class RuMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Russian month and optional year expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = RuConstants::monthPattern();
        $yearPattern = '[0-9]{1,4}(?![^\s]\d)(?:\s*(?:г\.?|года))?';

        preg_match_all("/(?<![\\pL\\pN])(?:в\\s*)?(?<month>{$monthPattern})(?:[\\s-]*(?<year>{$yearPattern}))?(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference, $text): ?ParsedResult {
            if ($this->followsDayNumber($text, $match[0][1])) {
                return null;
            }

            $month = RuConstants::MONTHS[mb_strtolower($match['month'][0])] ?? null;

            if ($month === null) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? RuConstants::year($yearText) : Years::findYearClosestToReference($reference->date, 1, $month);
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => 1,
            ])->addTag('parser/RUMonthNameParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components);
        }, $matches)));
    }

    protected function followsDayNumber(string $text, int $offset): bool
    {
        $before = substr($text, 0, $offset);

        if (! preg_match('/(?:^|\\s)(\\d{1,2})\\s+$/u', $before, $match)) {
            return false;
        }

        $day = (int) $match[1];

        return $day >= 1 && $day <= 31;
    }
}
