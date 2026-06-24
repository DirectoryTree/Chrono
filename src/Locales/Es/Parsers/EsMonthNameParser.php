<?php

namespace Chrono\Locales\Es\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Es\CreatesParsedComponents;
use Chrono\Locales\Es\EsConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class EsMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Spanish month-name dates and ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $weekday = EsConstants::weekdayPattern();
        $month = EsConstants::monthPattern();
        $results = [
            ...$this->parseSameMonthRanges($text, $reference, $month),
            ...$this->parseCrossMonthRanges($text, $reference, $month),
        ];

        preg_match_all('/\b(?:(?<weekday>'.$weekday.')\.?,?\s+)?(?<day>\d{1,2})(?:\s+de)?\s*(?<month>'.$month.')\.?(?:\s+de)?(?:\s+(?<year>\d{1,4})(?:\s*(?<era>a\.?\s*c\.?|d\.?\s*c\.?|ac|dc))?)?(?:\s+(?:a\s+las\s+)?(?<hour>\d{1,2})(?::(?<minute>\d{2}))?(?::(?<second>\d{2}))?\s*(?<meridiem>am|pm)?)?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $results = [
            ...$results,
            ...array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
                $day = (int) $match['day'][0];
                $month = $this->month($match['month'][0]);
                $year = ($match['year'][0] ?? '') !== ''
                    ? $this->year((int) $match['year'][0], $match['era'][0] ?? '')
                    : $this->closestYear($reference, $month, $day);

                if (! checkdate($month, $day, max(1, abs($year)))) {
                    return null;
                }

                $hour = ($match['hour'][0] ?? '') !== ''
                    ? $this->meridiemHour((int) $match['hour'][0], ($match['meridiem'][0] ?? '') ?: null)
                    : 12;
                $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;
                $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;
                $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $reference->date->timezone);
                $known = [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    ...((($match['hour'][0] ?? '') !== '') ? ['hour' => $hour, 'minute' => $minute, 'second' => $second] : []),
                ];

                if (($match['weekday'][0] ?? '') !== '') {
                    $known['weekday'] = EsConstants::weekdayNumber($match['weekday'][0]);
                }

                $components = $this->components($date, $known);
                $components->addTag('parser/ESMonthNameParser');

                return new ParsedResult($match[0][1], trim($match[0][0]), $components);
            }, $matches))),
        ];

        usort($results, fn (ParsedResult $left, ParsedResult $right): int => $left->index <=> $right->index ?: strlen($right->text) <=> strlen($left->text));

        return $results;
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseSameMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})\s*(?:-|a)\s*(?<endDay>\d{1,2})(?:\s+de)?\s*(?<month>'.$monthPattern.')\.?(?:\s+de)?(?:\s+(?<year>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $startDay = (int) $match['startDay'][0];
            $endDay = (int) $match['endDay'][0];
            $month = $this->month($match['month'][0]);
            $year = ($match['year'][0] ?? '') !== ''
                ? $this->year((int) $match['year'][0], '')
                : $this->closestYear($reference, $month, $startDay);

            if (! checkdate($month, $startDay, max(1, abs($year))) || ! checkdate($month, $endDay, max(1, abs($year)))) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $month, $endDay, 12, 0, 0, $reference->date->timezone);
            $startComponents = $this->components($start, ['year' => $year, 'month' => $month, 'day' => $startDay]);
            $endComponents = $this->components($end, ['year' => $year, 'month' => $month, 'day' => $endDay]);
            $startComponents->addTag('parser/ESMonthNameParser');
            $endComponents->addTag('parser/ESMonthNameParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $startComponents, $endComponents);
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseCrossMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})(?:\s+de)?\s*(?<startMonth>'.$monthPattern.')\.?\s*(?:-|a)\s*(?<endDay>\d{1,2})(?:\s+de)?\s*(?<endMonth>'.$monthPattern.')\.?(?:\s+de)?(?:\s+(?<year>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $startDay = (int) $match['startDay'][0];
            $endDay = (int) $match['endDay'][0];
            $startMonth = $this->month($match['startMonth'][0]);
            $endMonth = $this->month($match['endMonth'][0]);
            $year = ($match['year'][0] ?? '') !== ''
                ? $this->year((int) $match['year'][0], '')
                : $this->closestYear($reference, $startMonth, $startDay);

            if (! checkdate($startMonth, $startDay, max(1, abs($year))) || ! checkdate($endMonth, $endDay, max(1, abs($year)))) {
                return null;
            }

            $start = CarbonImmutable::create($year, $startMonth, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $endMonth, $endDay, 12, 0, 0, $reference->date->timezone);

            if (($match['year'][0] ?? '') === '' && $end->lessThan($start)) {
                $end = $end->addYear();
            }

            $startComponents = $this->components($start, ['year' => $start->year, 'month' => $startMonth, 'day' => $startDay]);
            $endComponents = $this->components($end, ['year' => $end->year, 'month' => $endMonth, 'day' => $endDay]);
            $startComponents->addTag('parser/ESMonthNameParser');
            $endComponents->addTag('parser/ESMonthNameParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $startComponents, $endComponents);
        }, $matches)));
    }

    protected function month(string $month): int
    {
        return EsConstants::monthNumber($month);
    }

    protected function closestYear(Reference $reference, int $month, int $day): int
    {
        $year = $reference->date->year;
        $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

        if ($date->diffInDays($reference->date, false) > 180) {
            return $year + 1;
        }

        if ($date->diffInDays($reference->date, false) < -180) {
            return $year - 1;
        }

        return $year;
    }

    protected function year(int $year, string $era): int
    {
        $era = $this->normalize($era);

        if ($era !== '') {
            return str_contains($era, 'ac') ? -$year : $year;
        }

        return $year < 100 && $year > 50 ? 1900 + $year : $year;
    }

    protected function normalize(string $value): string
    {
        return EsConstants::normalize($value);
    }
}
