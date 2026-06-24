<?php

namespace Chrono\Locales\Ru\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Ru\CreatesParsedComponents;
use Chrono\Locales\Ru\RuConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class RuMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Russian day-month and day-range-month expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $dayPattern = RuConstants::ordinalPattern();
        $monthPattern = RuConstants::monthPattern();
        $yearPattern = '[0-9]{1,4}(?![^\s]\d)(?:\s*(?:г\.?|года))?';

        preg_match_all("/(?<![\\pL\\pN])(?:с\\s*)?(?<day>{$dayPattern})(?:\\s*(?:по|до|-|–)\\s*(?<endday>{$dayPattern}))?\\s*(?:-|\\/|\\s*)\\s*(?<month>{$monthPattern})(?:\\s*(?<year>{$yearPattern}))?(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = RuConstants::MONTHS[mb_strtolower($match['month'][0])] ?? null;
            $day = RuConstants::ordinal($match['day'][0]);

            if ($month === null || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? RuConstants::year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if ($year < 1 || ! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ])->addTag('parser/RUMonthNameLittleEndianParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components, $end);
        }, $matches)));
    }

    /**
     * Build end components for same-month day ranges.
     */
    protected function endComponents(array $match, CarbonImmutable $start, int $month): ?ParsedComponents
    {
        $endDayText = $match['endday'][0] ?? '';

        if ($endDayText === '') {
            return null;
        }

        $endDay = RuConstants::ordinal($endDayText);

        if (! checkdate($month, $endDay, $start->year)) {
            return null;
        }

        return $this->components($start->day($endDay), [
            'month' => $month,
            'day' => $endDay,
        ])->addTag('parser/RUMonthNameLittleEndianParser');
    }
}
