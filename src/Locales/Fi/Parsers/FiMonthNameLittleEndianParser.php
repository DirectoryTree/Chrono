<?php

namespace Chrono\Locales\Fi\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Fi\CreatesParsedComponents;
use Chrono\Locales\Fi\FiConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class FiMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Finnish day-month and day-range-month expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = FiConstants::monthPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?<day>[0-9]{1,2})\\.?(?:\\s*(?:-|–|\\s)\\s*(?<endday>[0-9]{1,2})\\.?)?\\s*(?<month>{$monthPattern})(?:(?:-|\\/|,?\\s*)(?<year>[0-9]{4}(?![^\\s]\\d)))?(?=\\W|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = FiConstants::monthNumber($match['month'][0]);
            $day = (int) $match['day'][0];

            if ($month === null || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? (int) $yearText : Years::findYearClosestToReference($reference->date, $day, $month);

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ]);
            $components->addTag('parser/FIMonthNameLittleEndianParser');

            return new ParsedResult($match[0][1], $match[0][0], $components, $end);
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

        $endDay = (int) $endDayText;

        if (! checkdate($month, $endDay, $start->year)) {
            return null;
        }

        return $this->components($start->day($endDay), [
            'month' => $month,
            'day' => $endDay,
        ]);
    }
}
