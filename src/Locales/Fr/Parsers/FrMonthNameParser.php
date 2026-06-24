<?php

namespace Chrono\Locales\Fr\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Fr\CreatesParsedComponents;
use Chrono\Locales\Fr\FrConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class FrMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse French month-name date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $weekday = FrConstants::weekdayPattern();
        $month = FrConstants::monthPattern();
        $results = [
            ...$this->parseRepeatedMonthRanges($text, $reference, $month),
            ...$this->parseSameMonthRanges($text, $reference, $month),
            ...$this->parseCrossMonthRanges($text, $reference, $month),
        ];

        preg_match_all('/\b(?:(?<weekday>'.$weekday.')\.?,?\s*)?(?<day>\d{1,2})(?:er)?\s*(?<month>'.$month.')\.?(?:\s+(?<year>\d{1,4})(?!:)(?:\s*(?<era>ac|av\.?\s*j\.?\s*c\.?|p\.?\s*chr\.?\s*n\.?|ap\.?\s*j\.?\s*c\.?))?)?(?:\s+(?:à|a)?\s*(?<hour>\d{1,2})(?::(?<minute>\d{2}))?(?::(?<second>\d{2}))?\s*(?<meridiem>am|pm)?)?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $results = [
            ...$results,
            ...array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
                $day = (int) $match['day'][0];
                $month = $this->month($match['month'][0]);
                $yearText = $match['year'][0] ?? '';
                $year = $yearText !== ''
                    ? $this->year((int) $yearText, $match['era'][0] ?? '')
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
                    $known['weekday'] = $this->weekday($match['weekday'][0]);
                }

                $components = $this->components($date, $known);
                $components->addTag('parser/FRMonthNameParser');

                return new ParsedResult($match[0][1], trim($match[0][0]), $components);
            }, $matches))),
        ];

        usort($results, fn (ParsedResult $a, ParsedResult $b) => $a->index <=> $b->index ?: strlen($b->text) <=> strlen($a->text));

        return $results;
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseRepeatedMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})\s*(?<startMonth>'.$monthPattern.')\.?(?:\s+(?<startYear>\d{1,4}))?\s*(?:-|au)\s*(?<endDay>\d{1,2})\s*(?<endMonth>'.$monthPattern.')\.?(?:\s+(?<endYear>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $startDay = (int) $match['startDay'][0];
            $endDay = (int) $match['endDay'][0];
            $startMonth = $this->month($match['startMonth'][0]);
            $endMonth = $this->month($match['endMonth'][0]);
            $startYearText = $match['startYear'][0] ?? '';
            $endYearText = $match['endYear'][0] ?? '';
            $year = $startYearText !== ''
                ? $this->year((int) $startYearText, '')
                : ($endYearText !== ''
                    ? $this->year((int) $endYearText, '')
                    : $this->closestYear($reference, $startMonth, $startDay));
            $endYear = $endYearText !== '' ? $this->year((int) $endYearText, '') : $year;

            if (! checkdate($startMonth, $startDay, max(1, abs($year))) || ! checkdate($endMonth, $endDay, max(1, abs($endYear)))) {
                return null;
            }

            $start = CarbonImmutable::create($year, $startMonth, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($endYear, $endMonth, $endDay, 12, 0, 0, $reference->date->timezone);

            if ($endYearText === '' && $end->lessThan($start)) {
                $end = $end->addYear();
            }

            return new ParsedResult(
                $match[0][1],
                trim($match[0][0]),
                $this->components($start, ['year' => $start->year, 'month' => $startMonth, 'day' => $startDay]),
                $this->components($end, ['year' => $end->year, 'month' => $endMonth, 'day' => $endDay]),
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseSameMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})\s*(?:-|au)\s*(?<endDay>\d{1,2})\s*(?<month>'.$monthPattern.')\.?(?:\s+(?<year>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

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

            return new ParsedResult(
                $match[0][1],
                trim($match[0][0]),
                $this->components($start, ['year' => $year, 'month' => $month, 'day' => $startDay]),
                $this->components($end, ['year' => $year, 'month' => $month, 'day' => $endDay]),
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseCrossMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})\s*(?<startMonth>'.$monthPattern.')\.?\s*-\s*(?<endDay>\d{1,2})\s*(?<endMonth>'.$monthPattern.')\.?(?:\s+(?<year>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

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

            return new ParsedResult(
                $match[0][1],
                trim($match[0][0]),
                $this->components($start, ['year' => $start->year, 'month' => $startMonth, 'day' => $startDay]),
                $this->components($end, ['year' => $end->year, 'month' => $endMonth, 'day' => $endDay]),
            );
        }, $matches)));
    }

    /**
     * Resolve the month value.
     */
    protected function month(string $month): int
    {
        return FrConstants::monthNumber($month);
    }

    /**
     * Resolve the weekday value.
     */
    protected function weekday(string $weekday): int
    {
        return FrConstants::weekdayNumber($weekday);
    }

    /**
     * Resolve the year value.
     */
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

    /**
     * Resolve the year value.
     */
    protected function year(int $year, string $era): int
    {
        $era = $this->normalize($era);

        if ($era !== '') {
            return str_contains($era, 'ac') || str_contains($era, 'avjc') ? -$year : $year;
        }

        return $year < 100 && $year > 50 ? 1900 + $year : $year;
    }

    /**
     * Resolve the hour value.
     */
    protected function meridiemHour(int $hour, ?string $meridiem): int
    {
        $meridiem = strtolower((string) $meridiem);

        if ($meridiem === 'pm' && $hour < 12) {
            return $hour + 12;
        }

        if ($meridiem === 'am' && $hour === 12) {
            return 0;
        }

        return $hour;
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return FrConstants::normalize($value);
    }
}
