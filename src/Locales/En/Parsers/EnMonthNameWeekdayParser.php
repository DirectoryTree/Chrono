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

class EnMonthNameWeekdayParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();
        $weekdayPattern = 'sunday|sun|monday|mon|tuesday|tues|tue|wednesday|wed|thursday|thurs|thur|thu|friday|fri|saturday|sat';

        preg_match_all('/\b(?<weekday>'.$weekdayPattern.')\.?,?\s+(?<month>'.$monthPattern.')\.?,?\s+(?<day>\d{1,2})(?:st|nd|rd|th)?(?:,?\s*(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            return $this->monthDateResult($match, $reference, $match['month'][0], $match['day'][0], $match['year'][0] ?? '', $match['era'][0] ?? '', $match['weekday'][0] ?? '');
        }, $matches)));
    }

    /**
     * Create a parsed result from a month/date match.
     */
    protected function monthDateResult(array $match, Reference $reference, string $monthText, string $dayText, string $yearText = '', string $era = '', string $weekdayText = ''): ?ParsedResult
    {
        $month = EnConstants::MONTHS[strtolower($monthText)];
        $day = (int) $dayText;
        $year = $yearText !== '' ? $this->year((int) $yearText, $era) : Years::findYearClosestToReference($reference->date, $day, $month);

        if (! checkdate($month, $day, max(1, abs($year)))) {
            return null;
        }

        $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

        return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
            ...($yearText !== '' ? ['year' => $year] : []),
            'month' => $month,
            'day' => $day,
            ...($weekdayText !== '' ? ['weekday' => EnConstants::WEEKDAYS[strtolower($weekdayText)]] : []),
        ]));
    }

    /**
     * Resolve the year value.
     */
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
