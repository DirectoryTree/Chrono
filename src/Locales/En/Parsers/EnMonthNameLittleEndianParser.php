<?php

namespace DirectoryTree\Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;
use DirectoryTree\Chrono\Locales\En\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\En\EnConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class EnMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        return [
            ...$this->parseLittleEndianMonthNameDates($text, $reference),
            ...$this->parseSeparatedLittleEndianDates($text, $reference),
            ...$this->parseCompactLittleEndianDates($text, $reference),
        ];
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseLittleEndianMonthNameDates(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();
        $weekdayPattern = 'sunday|sun|monday|mon|tuesday|tues|tue|wednesday|wed|thursday|thurs|thur|thu|friday|fri|saturday|sat';

        preg_match_all('/\b(?:(?<weekday>'.$weekdayPattern.')\.?,?\s*)?(?<day>\d{1,2})(?:st|nd|rd|th)?(?:\s+of)?\s+(?<month>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            return $this->monthDateResult(
                $match,
                $reference,
                $match['month'][0],
                $match['day'][0],
                $match['year'][0] ?? '',
                $match['era'][0] ?? '',
                $match['weekday'][0] ?? ''
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseSeparatedLittleEndianDates(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<day>\d{1,2})(?:st|nd|rd|th)?\s*[-\/]\s*(?<month>'.$monthPattern.')\.?(?:\s*[-\/,]?\s*(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            return $this->monthDateResult($match, $reference, $match['month'][0], $match['day'][0], $match['year'][0] ?? '', $match['era'][0] ?? '');
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseCompactLittleEndianDates(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();
        $weekdayPattern = 'sunday|sun|monday|mon|tuesday|tues|tue|wednesday|wed|thursday|thurs|thur|thu|friday|fri|saturday|sat';

        preg_match_all('/\b(?:(?<weekday>'.$weekdayPattern.')\.?,?\s*)?(?<day>\d{1,2})(?:st|nd|rd|th)?(?<month>'.$monthPattern.')\.?(?:\s*,?\s*(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

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
