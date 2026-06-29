<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Locales\En\EnConstants;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class EnMonthNameTrailingYearParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();
        $weekdayPattern = 'sunday|sun|monday|mon|tuesday|tues|tue|wednesday|wed|thursday|thurs|thur|thu|friday|fri|saturday|sat';

        return [
            ...$this->parseTrailingYearDayRanges($text, $reference, $monthPattern, $weekdayPattern),
            ...$this->parseTrailingYearTimeRanges($text, $reference, $monthPattern, $weekdayPattern),
            ...$this->parseTrailingYearDateTimes($text, $reference, $monthPattern, $weekdayPattern),
        ];
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseTrailingYearDayRanges(string $text, Reference $reference, string $monthPattern, string $weekdayPattern): array
    {
        preg_match_all('/\b(?:(?:'.$weekdayPattern.')\s+)?(?<month>'.$monthPattern.')\.?\s+(?<day>\d{1,2})(?:st|nd|rd|th)?\s*(?:-|to|until|through|till)\s*(?<endday>\d{1,2})(?:st|nd|rd|th)?,?\s+(?<hour>\d{1,2}):(?<minute>\d{2})(?::(?<second>\d{2}))?\s+(?<year>\d{4})\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $day = (int) $match['day'][0];
            $endDay = (int) $match['endday'][0];
            $year = (int) $match['year'][0];
            $hour = (int) $match['hour'][0];
            $minute = (int) $match['minute'][0];
            $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;

            if (! checkdate($month, $day, $year) || ! checkdate($month, $endDay, $year) || $hour > 23 || $minute > 59 || $second > 59) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $month, $endDay, $hour, $minute, $second, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->dateTimeComponents($start),
                $this->dateTimeComponents($end)
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseTrailingYearTimeRanges(string $text, Reference $reference, string $monthPattern, string $weekdayPattern): array
    {
        preg_match_all('/\b(?:(?:'.$weekdayPattern.')\s+)?(?<month>'.$monthPattern.')\.?\s+(?<day>\d{1,2})(?:st|nd|rd|th)?,?\s+(?<hour>\d{1,2}):(?<minute>\d{2})(?::(?<second>\d{2}))?\s*(?:-|to|until|through|till)\s*(?<endhour>\d{1,2}):(?<endminute>\d{2})(?::(?<endsecond>\d{2}))?\s+(?<year>\d{4})\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $day = (int) $match['day'][0];
            $year = (int) $match['year'][0];
            $hour = (int) $match['hour'][0];
            $minute = (int) $match['minute'][0];
            $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;
            $endHour = (int) $match['endhour'][0];
            $endMinute = (int) $match['endminute'][0];
            $endSecond = ($match['endsecond'][0] ?? '') !== '' ? (int) $match['endsecond'][0] : 0;

            if (! checkdate($month, $day, $year) || max($hour, $endHour) > 23 || max($minute, $endMinute) > 59 || max($second, $endSecond) > 59) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $month, $day, $endHour, $endMinute, $endSecond, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->dateTimeComponents($start),
                $this->dateTimeComponents($end)
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseTrailingYearDateTimes(string $text, Reference $reference, string $monthPattern, string $weekdayPattern): array
    {
        preg_match_all('/\b(?:(?:'.$weekdayPattern.')\s+)?(?<month>'.$monthPattern.')\.?\s+(?<day>\d{1,2})(?:st|nd|rd|th)?(?:,?\s+)(?<hour>\d{1,2}):(?<minute>\d{2})(?::(?<second>\d{2}))?(?:\s*(?<timezone>[A-Z]{2,4}))?\s+(?<year>\d{4})\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $day = (int) $match['day'][0];
            $year = (int) $match['year'][0];
            $hour = (int) $match['hour'][0];
            $minute = (int) $match['minute'][0];
            $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;

            if (! checkdate($month, $day, $year) || $hour > 23 || $minute > 59 || $second > 59) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $reference->date->timezone);

            $result = new ParsedResult($match[0][1], $match[0][0], $this->dateTimeComponents($date));

            $timezone = strtoupper($match['timezone'][0] ?? '');

            if ($timezone !== '' && ($offset = $this->timezoneOffset($timezone)) !== null) {
                $result->start->assign('timezoneOffset', $offset);
            }

            return $result;
        }, $matches)));
    }

    /**
     * @return array<string, int>
     */
    protected function dateTimeKnown(CarbonImmutable $date): array
    {
        return [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            'hour' => $date->hour,
            'minute' => $date->minute,
            'second' => $date->second,
        ];
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function dateTimeComponents(CarbonImmutable $date): ParsedComponents
    {
        $components = $this->components($date, $this->dateTimeKnown($date));

        if ($date->hour > 12) {
            return $components->assign('meridiem', Meridiem::PM->value);
        }

        return $components->imply('meridiem', $date->hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);
    }

    /**
     * Resolve the timezone offset.
     */
    protected function timezoneOffset(string $timezone): ?int
    {
        return [
            'UTC' => 0,
            'GMT' => 0,
            'PST' => -480,
            'PDT' => -420,
            'MST' => -420,
            'MDT' => -360,
            'CST' => -360,
            'CDT' => -300,
            'EST' => -300,
            'EDT' => -240,
        ][$timezone] ?? null;
    }
}
