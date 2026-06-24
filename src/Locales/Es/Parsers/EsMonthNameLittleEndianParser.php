<?php

namespace Chrono\Locales\Es\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Es\CreatesParsedComponents;
use Chrono\Locales\Es\EsConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class EsMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Spanish little-endian month-name dates and same-month ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EsConstants::monthPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?<day>[0-9]{1,2})(?:º|ª|°)?(?:\\s*(?:desde|de|-|–|ao?|\\s)\\s*(?<endday>[0-9]{1,2})(?:º|ª|°)?)?\\s*(?:de)?\\s*(?:-|\\/|\\s*(?:de|,)?\\s*)(?<month>{$monthPattern})(?:\\s*(?:de|,)?\\s*(?<year>[0-9]{1,4}(?:\\s*(?:a\\.?\\s*c\\.?|d\\.?\\s*c\\.?|ac|dc))?))?(?=\\W|$)/iu",
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
        return EsConstants::monthNumber($month);
    }

    protected function year(string $year): int
    {
        $normalized = $this->normalize($year);
        $number = (int) preg_replace('/[^0-9]+/u', '', $year);

        if (str_contains($normalized, 'ac')) {
            return -$number;
        }

        if (str_contains($normalized, 'dc')) {
            return $number;
        }

        return $number < 100 && $number > 50 ? 1900 + $number : $number;
    }

    protected function normalize(string $value): string
    {
        return EsConstants::normalize($value);
    }
}
