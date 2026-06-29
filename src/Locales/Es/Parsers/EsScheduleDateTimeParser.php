<?php

namespace Chrono\Locales\Es\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Es\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class EsScheduleDateTimeParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Spanish schedule-style slash date time expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $weekday = 'lunes|martes|mi[eé]rcoles|jueves|viernes|s[aá]bado|domingo';

        preg_match_all('/\b(?:(?<weekday>'.$weekday.'),?\s+)?(?<month>\d{1,2})\/(?<day>\d{1,2})\/(?<year>\d{2,4}),?\s+(?<hour>\d{1,2})(?:(?<minute>\d{2})|:(?<minuteColon>\d{2}))?\s*(?<meridiem>am|pm)?(?:\s*-\s*(?<endhour>\d{1,2})(?:(?<endminute>\d{2})|:(?<endminuteColon>\d{2}))?\s*(?<endmeridiem>am|pm)?)?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = (int) $match['month'][0];
            $day = (int) $match['day'][0];
            $year = $this->year((int) $match['year'][0]);
            $hour = $this->meridiemHour((int) $match['hour'][0], ($match['meridiem'][0] ?? '') ?: $this->impliedStartMeridiem($match));
            $minute = $this->minute($match, 'minute', 'minuteColon');

            if (! checkdate($month, $day, $year) || $hour > 23 || $minute > 59) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $start);

            $known = [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $start->hour,
                'minute' => $start->minute,
                'second' => 0,
            ];

            if (($match['weekday'][0] ?? '') !== '') {
                $known['weekday'] = $start->dayOfWeek;
            }

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, $known)->addTag('parser/ESScheduleDateTimeParser'),
                $end,
            );
        }, $matches)));
    }

    /**
     * Build end components for schedule ranges.
     */
    protected function endComponents(array $match, CarbonImmutable $start): ?ParsedComponents
    {
        if (($match['endhour'][0] ?? '') === '') {
            return null;
        }

        $endHour = $this->meridiemHour((int) $match['endhour'][0], ($match['endmeridiem'][0] ?? '') ?: (($match['meridiem'][0] ?? '') ?: null));
        $endMinute = $this->minute($match, 'endminute', 'endminuteColon');

        if ($endHour > 23 || $endMinute > 59) {
            return null;
        }

        $end = $start->hour($endHour)->minute($endMinute)->second(0)->millisecond(0);

        if ($end->lessThan($start)) {
            $end = $end->addDay();
        }

        return $this->components($end, [
            'year' => $end->year,
            'month' => $end->month,
            'day' => $end->day,
            'hour' => $end->hour,
            'minute' => $end->minute,
            'second' => 0,
        ])->addTag('parser/ESScheduleDateTimeParser');
    }

    /**
     * Extract a compact or colon-separated minute value.
     */
    protected function minute(array $match, string $compactKey, string $colonKey): int
    {
        return ($match[$compactKey][0] ?? '') !== ''
            ? (int) $match[$compactKey][0]
            : (($match[$colonKey][0] ?? '') !== '' ? (int) $match[$colonKey][0] : 0);
    }

    /**
     * Infer the start meridiem from an explicit end meridiem.
     */
    protected function impliedStartMeridiem(array $match): ?string
    {
        if (($match['endmeridiem'][0] ?? '') === '') {
            return null;
        }

        $endMeridiem = strtolower($match['endmeridiem'][0]);
        $startHour = (int) $match['hour'][0];
        $endHour = (int) $match['endhour'][0];

        if ($endMeridiem === 'pm' && $startHour <= $endHour) {
            return 'pm';
        }

        if ($endMeridiem === 'am' && ($startHour === 12 || $startHour <= $endHour)) {
            return 'am';
        }

        return null;
    }

    /**
     * Convert a 12-hour clock value into a 24-hour value.
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
     * Expand a two-digit year.
     */
    protected function year(int $year): int
    {
        if ($year < 100) {
            return $year > 50 ? 1900 + $year : 2000 + $year;
        }

        return $year;
    }
}
