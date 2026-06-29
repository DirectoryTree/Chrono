<?php

namespace Chrono\Locales\Fr\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Fr\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class FrIsoDateTimeRangeParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse ISO-style French date time ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/\b(?<year>\d{4})-(?<month>\d{1,2})-(?<day>\d{1,2})\s+(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*-\s*(?<endHour>\d{1,2})(?:(?:h|:)(?<endMinute>\d{2})?)?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $year = (int) $match['year'][0];
            $month = (int) $match['month'][0];
            $day = (int) $match['day'][0];
            $hour = (int) $match['hour'][0];
            $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;
            $endHour = (int) $match['endHour'][0];
            $endMinute = ($match['endMinute'][0] ?? '') !== '' ? (int) $match['endMinute'][0] : 0;

            if (! checkdate($month, $day, $year) || $hour > 23 || $minute > 59 || $endHour > 23 || $endMinute > 59) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $month, $day, $endHour, $endMinute, 0, $reference->date->timezone);

            if ($end->lessThanOrEqualTo($start)) {
                $end = $end->addDay();
            }

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'hour' => $hour,
                    'minute' => $minute,
                    'second' => 0,
                ])->addTag('parser/FRIsoDateTimeRangeParser'),
                $this->components($end, [
                    'year' => $end->year,
                    'month' => $end->month,
                    'day' => $end->day,
                    'hour' => $endHour,
                    'minute' => $endMinute,
                    'second' => 0,
                ])->addTag('parser/FRIsoDateTimeRangeParser'),
            );
        }, $matches)));
    }
}
