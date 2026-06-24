<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Locales\En\EnConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class EnMonthNameMiddleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Create an English middle-endian month-name parser.
     */
    public function __construct(
        protected readonly bool $shouldSkipYearLikeDate = false,
    ) {}

    /**
     * Parse English middle-endian month-name date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        return [
            ...$this->parseMiddleEndianDates($text, $reference, $options),
            ...$this->parseSeparatedMiddleEndianDates($text, $reference),
        ];
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMiddleEndianDates(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<month>'.$monthPattern.')\.?\s+(?<day>\d{1,2})(?:st|nd|rd|th)?(?:\s*(?:-|to|through)\s*(?<endday>\d{1,2})(?:st|nd|rd|th)?)?(?:(?:,\s*|\s+)(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?(?:\s+(?:at\s+|from\s+)?(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?<meridiem>am|pm)?)?(?:\s*(?:-|to)\s*(?<endhour>\d{1,2})(?::(?<endminute>\d{2}))?\s*(?<endmeridiem>am|pm)?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference, $options): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $day = (int) $match['day'][0];

            if ($this->shouldSkipYearLikeDate && ($match['endday'][0] ?? '') === '' && ($match['year'][0] ?? '') === '' && preg_match('/^2[0-5]$/', $match['day'][0]) === 1) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? $this->year((int) $yearText, $match['era'][0] ?? '') : Years::findYearClosestToReference($reference->date, $day, $month);
            $hour = ($match['hour'][0] ?? '') !== '' ? $this->meridiemHour((int) $match['hour'][0], ($match['meridiem'][0] ?? '') ?: null) : 12;
            $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;

            if (! checkdate($month, $day, max(1, abs($year)))) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);

            if ($yearText === '' && $options->forwardDate() && $date->lt($reference->date)) {
                $date = $date->addYear();
                $year = $date->year;
            }

            $end = $this->endComponents($match, $date);

            if ((($match['endday'][0] ?? '') !== '' || ($match['endhour'][0] ?? '') !== '') && $end === null) {
                return null;
            }

            return (new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($date, $this->known($match, $yearText, $year, $month, $day, $hour, $minute)),
                $end
            ))->addTag('parser/ENMonthNameMiddleEndianParser');
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseSeparatedMiddleEndianDates(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<month>'.$monthPattern.')\.?\s*[-\/]\s*(?<day>\d{1,2})(?:st|nd|rd|th)?(?:\s*[-\/,]\s*(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            return $this->monthDateResult($match, $reference, $match['month'][0], $match['day'][0], $match['year'][0] ?? '', $match['era'][0] ?? '');
        }, $matches)));
    }

    /**
     * Create a parsed result from a month/date match.
     */
    protected function monthDateResult(array $match, Reference $reference, string $monthText, string $dayText, string $yearText = '', string $era = ''): ?ParsedResult
    {
        $month = EnConstants::MONTHS[strtolower($monthText)];
        $day = (int) $dayText;
        $year = $yearText !== '' ? $this->year((int) $yearText, $era) : Years::findYearClosestToReference($reference->date, $day, $month);

        if (! checkdate($month, $day, max(1, abs($year)))) {
            return null;
        }

        $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

        return (new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
            ...($yearText !== '' ? ['year' => $year] : []),
            'month' => $month,
            'day' => $day,
        ])))->addTag('parser/ENMonthNameMiddleEndianParser');
    }

    /**
     * Get known.
     */
    protected function known(array $match, string $yearText, int $year, int $month, int $day, int $hour, int $minute): array
    {
        return [
            ...($yearText !== '' ? ['year' => $year] : []),
            'month' => $month,
            'day' => $day,
            ...(($match['hour'][0] ?? '') !== '' ? ['hour' => $hour, 'minute' => $minute] : []),
        ];
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function endComponents(array $match, CarbonImmutable $start): ?ParsedComponents
    {
        $endDay = $match['endday'][0] ?? '';
        $endHour = $match['endhour'][0] ?? '';

        if ($endDay === '' && $endHour === '') {
            return null;
        }

        $date = $start;
        $known = ['month' => $start->month];

        if ($endDay !== '') {
            if (! checkdate($date->month, (int) $endDay, max(1, abs($date->year)))) {
                return null;
            }

            $date = $date->day((int) $endDay);
            $known['day'] = (int) $endDay;
        }

        if ($endHour !== '') {
            $endMeridiem = ($match['endmeridiem'][0] ?? '') ?: (($match['meridiem'][0] ?? '') ?: null);
            $hour = $this->meridiemHour((int) $endHour, $endMeridiem);
            $minute = ($match['endminute'][0] ?? '') !== '' ? (int) $match['endminute'][0] : 0;
            $date = $date->hour($hour)->minute($minute);
            $known['hour'] = $hour;
            $known['minute'] = $minute;
        }

        $components = $this->components($date, $known);

        if ($endDay === '' && $endHour !== '' && $components->date()->lt($start)) {
            $components->addDurationAsImplied(['day' => 1]);
        }

        return $components;
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
