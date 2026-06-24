<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Locales\En\EnConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class EnMonthNameRangeParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        return [
            ...$this->parseCompactLittleEndianDateTimeRanges($text, $reference),
            ...$this->parseMonthOnlyRanges($text, $reference, $options),
            ...$this->parseMiddleEndianCrossMonthRanges($text, $reference),
            ...$this->parseCrossMonthRanges($text, $reference),
            ...$this->parseLittleEndianRanges($text, $reference),
        ];
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseCompactLittleEndianDateTimeRanges(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();
        $weekdayPattern = 'sunday|sun|monday|mon|tuesday|tues|tue|wednesday|wed|thursday|thurs|thur|thu|friday|fri|saturday|sat';

        preg_match_all('/\b(?:(?<weekday>'.$weekdayPattern.')\.?,?\s*)?(?<day>\d{1,2})(?:st|nd|rd|th)?(?<month>'.$monthPattern.')\.?\s+(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?<meridiem>am|pm)\s*(?:-|to|until|through|till)\s*(?:(?<endweekday>'.$weekdayPattern.')\.?,?\s*(?<endday>\d{1,2})(?:st|nd|rd|th)?(?<endmonth>'.$monthPattern.')\.?\s+)?(?<endhour>\d{1,2})(?::(?<endminute>\d{2}))?\s*(?<endmeridiem>am|pm)\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $day = (int) $match['day'][0];
            $year = Years::findYearClosestToReference($reference->date, $day, $month);
            $hour = $this->meridiemHour((int) $match['hour'][0], $match['meridiem'][0]);
            $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;

            $endMonth = ($match['endmonth'][0] ?? '') !== '' ? EnConstants::MONTHS[strtolower($match['endmonth'][0])] : $month;
            $endDay = ($match['endday'][0] ?? '') !== '' ? (int) $match['endday'][0] : $day;
            $endYear = ($match['endmonth'][0] ?? '') !== '' ? Years::findYearClosestToReference($reference->date, $endDay, $endMonth) : $year;
            $endHour = $this->meridiemHour((int) $match['endhour'][0], $match['endmeridiem'][0]);
            $endMinute = ($match['endminute'][0] ?? '') !== '' ? (int) $match['endminute'][0] : 0;

            if (
                ! checkdate($month, $day, max(1, abs($year)))
                || ! checkdate($endMonth, $endDay, max(1, abs($endYear)))
                || $hour > 23
                || $minute > 59
                || $endHour > 23
                || $endMinute > 59
            ) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($endYear, $endMonth, $endDay, $endHour, $endMinute, 0, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, [
                    'month' => $month,
                    'day' => $day,
                    'hour' => $hour,
                    'minute' => $minute,
                    ...(($match['weekday'][0] ?? '') !== '' ? ['weekday' => $start->dayOfWeek] : []),
                ]),
                $this->components($end, [
                    'month' => $endMonth,
                    'day' => $endDay,
                    'hour' => $endHour,
                    'minute' => $endMinute,
                    ...(($match['endweekday'][0] ?? '') !== '' ? ['weekday' => $end->dayOfWeek] : []),
                ])
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMonthOnlyRanges(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?:from\s+)?(?<startmonth>'.$monthPattern.')\.?\s*(?:-|to|until|through|thru|till)\s*(?<endmonth>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{4}))?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference, $options): ?ParsedResult {
            $startMonth = EnConstants::MONTHS[strtolower($match['startmonth'][0])];
            $endMonth = EnConstants::MONTHS[strtolower($match['endmonth'][0])];
            $yearText = $match['year'][0] ?? '';

            if ($yearText !== '') {
                $endYear = (int) $yearText;
                $startYear = $startMonth > $endMonth ? $endYear - 1 : $endYear;
            } elseif ($options->forwardDate()) {
                $startYear = $reference->date->year;
                $endYear = $startMonth > $endMonth ? $startYear + 1 : $startYear;
            } else {
                $startYear = Years::findYearClosestToReference($reference->date, 1, $startMonth);
                $endYear = $startMonth > $endMonth ? $startYear + 1 : $startYear;
            }

            $start = CarbonImmutable::create($startYear, $startMonth, 1, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($endYear, $endMonth, 1, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, [
                    ...($yearText !== '' ? ['year' => $startYear] : []),
                    'month' => $startMonth,
                ]),
                $this->components($end, [
                    ...($yearText !== '' ? ['year' => $endYear] : []),
                    'month' => $endMonth,
                ])
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMiddleEndianCrossMonthRanges(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<startmonth>'.$monthPattern.')\.?\s+(?<startday>\d{1,2})(?:st|nd|rd|th)?(?:(?:,\s*|\s+)(?<startyear>\d{1,4})(?:\s*(?<startera>BCE|CE|BC|AD|BE))?)?\s*(?:-|to|until|through|till)\s*(?<endmonth>'.$monthPattern.')\.?\s+(?<endday>\d{1,2})(?:st|nd|rd|th)?(?:(?:,\s*|\s+)(?<endyear>\d{1,4})(?:\s*(?<endera>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return $this->crossMonthRangeResults($matches, $reference, monthFirst: true);
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseCrossMonthRanges(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<startday>\d{1,2})(?:st|nd|rd|th)?\s+(?<startmonth>'.$monthPattern.')\.?(?:(?:,\s*|\s+)(?<startyear>\d{1,4})(?:\s*(?<startera>BCE|CE|BC|AD|BE))?)?\s*(?:-|to|until|through|till)\s*(?<endday>\d{1,2})(?:st|nd|rd|th)?\s+(?<endmonth>'.$monthPattern.')\.?(?:(?:,\s*|\s+)(?<endyear>\d{1,4})(?:\s*(?<endera>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return $this->crossMonthRangeResults($matches, $reference, monthFirst: false);
    }

    /**
     * @param  array<int, array<string, array{0: string, 1: int}>>  $matches
     * @return array<int, ParsedResult>
     */
    protected function crossMonthRangeResults(array $matches, Reference $reference, bool $monthFirst): array
    {
        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $startMonth = EnConstants::MONTHS[strtolower($match['startmonth'][0])];
            $endMonth = EnConstants::MONTHS[strtolower($match['endmonth'][0])];
            $startDay = (int) $match['startday'][0];
            $endDay = (int) $match['endday'][0];
            $startYearText = $match['startyear'][0] ?? '';
            $endYearText = $match['endyear'][0] ?? '';

            $year = match (true) {
                $endYearText !== '' => $this->year((int) $endYearText, $match['endera'][0] ?? ''),
                $startYearText !== '' => $this->year((int) $startYearText, $match['startera'][0] ?? ''),
                default => Years::findYearClosestToReference($reference->date, $startDay, $startMonth),
            };

            $startYear = $startYearText !== ''
                ? $this->year((int) $startYearText, $match['startera'][0] ?? '')
                : $year;
            $endYear = $endYearText !== ''
                ? $this->year((int) $endYearText, $match['endera'][0] ?? '')
                : $year;

            if (! checkdate($startMonth, $startDay, max(1, abs($startYear))) || ! checkdate($endMonth, $endDay, max(1, abs($endYear)))) {
                return null;
            }

            $start = CarbonImmutable::create($startYear, $startMonth, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($endYear, $endMonth, $endDay, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, [
                    ...(($startYearText !== '' || $endYearText !== '') ? ['year' => $startYear] : []),
                    'month' => $startMonth,
                    'day' => $startDay,
                ]),
                $this->components($end, [
                    ...(($startYearText !== '' || $endYearText !== '') ? ['year' => $endYear] : []),
                    'month' => $endMonth,
                    'day' => $endDay,
                ])
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseLittleEndianRanges(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<startday>\d{1,2})(?:st|nd|rd|th)?\s*(?:-|to|until|through|till)\s*(?<endday>\d{1,2})(?:st|nd|rd|th)?\s+(?<month>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $startDay = (int) $match['startday'][0];
            $endDay = (int) $match['endday'][0];
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== ''
                ? $this->year((int) $yearText, $match['era'][0] ?? '')
                : Years::findYearClosestToReference($reference->date, $startDay, $month);

            if (! checkdate($month, $startDay, max(1, abs($year))) || ! checkdate($month, $endDay, max(1, abs($year)))) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $month, $endDay, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, [
                    ...($yearText !== '' ? ['year' => $year] : []),
                    'month' => $month,
                    'day' => $startDay,
                ]),
                $this->components($end, [
                    ...($yearText !== '' ? ['year' => $year] : []),
                    'month' => $month,
                    'day' => $endDay,
                ])
            );
        }, $matches)));
    }

    protected function year(int $year, string $era): int
    {
        $year = match (strtoupper($era)) {
            'BCE', 'BC' => -$year,
            'BE' => $year - 543,
            default => $year,
        };

        if ($era === '' && $year < 100) {
            return $year > 50 ? $year + 1900 : $year + 2000;
        }

        return $year;
    }
}
