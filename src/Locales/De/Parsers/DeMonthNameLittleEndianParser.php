<?php

namespace Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\De\CreatesParsedComponents;
use Chrono\Locales\De\DeConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class DeMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse German little-endian month-name dates and same-month ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = DeConstants::monthPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?:am\\s*?)?(?:den\\s*?)?(?<day>[0-9]{1,2})\\.(?:\\s*(?:bis(?:\\s*(?:am|zum))?|-|–|\\s)\\s*(?<endday>[0-9]{1,2})\\.?)?\\s*(?<month>{$monthPattern})(?:(?:-|\\/|,?\\s*)(?<year>[0-9]{1,4}(?:\\s*[vn]\\.?\\s*(?:C(?:hr)?|(?:u\\.?|d\\.?(?:\\s*g\\.?)?)?\\s*Z)\\.?|\\s*(?:u\\.?|d\\.?(?:\\s*g\\.)?)\\s*Z\\.?)?)(?![^\\s]\\d))?(?=\\W|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $day = (int) $match['day'][0];

            if ($day > 31) {
                return null;
            }

            $month = $this->month($match['month'][0]);
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? $this->year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if (! checkdate($month, $day, max(1, abs($year)))) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ]), $end);
        }, $matches)));
    }

    /**
     * Build end components for same-month ranges.
     */
    protected function endComponents(array $match, CarbonImmutable $start, int $month): ?ParsedComponents
    {
        $endDayText = $match['endday'][0] ?? '';

        if ($endDayText === '') {
            return null;
        }

        $endDay = (int) $endDayText;

        if (! checkdate($month, $endDay, max(1, abs($start->year)))) {
            return null;
        }

        return $this->components($start->day($endDay), [
            'month' => $month,
            'day' => $endDay,
        ]);
    }

    protected function month(string $month): int
    {
        return DeConstants::monthNumber($month);
    }

    protected function year(string $year): int
    {
        if (preg_match('/v/iu', $year) === 1) {
            return -((int) preg_replace('/[^0-9]+/u', '', $year));
        }

        if (preg_match('/[nz]/iu', $year) === 1) {
            return (int) preg_replace('/[^0-9]+/u', '', $year);
        }

        $year = (int) preg_replace('/[^0-9]+/u', '', $year);

        if ($year < 100) {
            return $year > 50 ? $year + 1900 : $year + 2000;
        }

        return $year;
    }

    protected function normalize(string $value): string
    {
        return DeConstants::normalize($value);
    }
}
