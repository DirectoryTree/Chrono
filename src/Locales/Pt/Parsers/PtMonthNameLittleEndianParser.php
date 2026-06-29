<?php

namespace Chrono\Locales\Pt\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Pt\CreatesParsedComponents;
use Chrono\Locales\Pt\PtConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class PtMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Portuguese day-month and day-range-month expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = PtConstants::monthPattern();
        $yearPattern = '[0-9]{1,4}(?![^\s]\d)(?:\s*[ad]\.?\s*c\.?|\s*a\.?\s*d\.?)?';

        preg_match_all("/(?<![\\pL\\pN])(?<day>[0-9]{1,2})(?:º|ª|°)?(?:\\s*(?:desde|de|-|–|ao?|até|\\s)\\s*(?<endday>[0-9]{1,2})(?:º|ª|°)?)?\\s*(?:de)?\\s*(?:-|\\/|\\s*(?:de|,)?\\s*)(?<month>{$monthPattern})(?:\\s*(?:de|,)?\\s*(?<year>{$yearPattern}))?(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = PtConstants::monthNumber($match['month'][0]);
            $day = (int) $match['day'][0];

            if ($month === null || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? PtConstants::year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if (! $this->validDate($year, $month, $day)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ])->addTag('parser/PTMonthNameLittleEndianParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components, $end);
        }, $matches)));
    }

    /**
     * Determine whether a parsed Portuguese date is calendar-valid.
     */
    protected function validDate(int $year, int $month, int $day): bool
    {
        if ($year < 1) {
            return $month >= 1
                && $month <= 12
                && $day >= 1
                && $day <= 31;
        }

        return checkdate($month, $day, $year);
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
        ])->addTag('parser/PTMonthNameLittleEndianParser');
    }
}
