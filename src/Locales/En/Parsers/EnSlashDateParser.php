<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Locales\En\EnConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Reference;

readonly class EnSlashDateParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Create an English slash-date parser.
     */
    public function __construct(
        protected readonly bool $littleEndian = false,
    ) {}

    /**
     * Parse English slash-style dates and English slash-date extensions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $results = [
            ...(new SlashDateFormatParser(littleEndian: $this->littleEndian))->parse($text, $reference, $options),
            ...$this->parseMonthNameDates($text, $reference),
            ...$this->parseWeekdayPrefixedDates($text, $reference),
        ];

        usort($results, fn (ParsedResult $left, ParsedResult $right): int => $left->index <=> $right->index ?: strlen($right->text) <=> strlen($left->text));

        return $results;
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMonthNameDates(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<day>\d{1,2})\/(?<month>'.$monthPattern.')\/(?<year>\d{2,4})(?:(?:\s+|:)(?<hour>\d{1,2}):(?<minute>\d{2})(?::(?<second>\d{2}))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $day = (int) $match['day'][0];
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $year = $this->year($match['year'][0], $reference);
            $hour = ($match['hour'][0] ?? '') !== '' ? (int) $match['hour'][0] : 12;
            $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;
            $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;

            if (! checkdate($month, $day, $year) || $hour > 23 || $minute > 59 || $second > 59) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $reference->date->timezone);
            $components = $this->components($date, [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                ...(($match['hour'][0] ?? '') !== '' ? ['hour' => $hour, 'minute' => $minute, 'second' => $second] : []),
            ]);
            $components->addTag('parser/ENSlashDateParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseWeekdayPrefixedDates(string $text, Reference $reference): array
    {
        $weekdayPattern = EnConstants::weekdayPattern();

        preg_match_all('/\b(?<weekday>'.$weekdayPattern.')\.?,?\s+(?<month>\d{1,2})[\/.-](?<day>\d{1,2})(?:[\/.-](?<year>\d{2,4}))?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $first = (int) $match['month'][0];
            $second = (int) $match['day'][0];
            $month = $this->littleEndian ? $second : $first;
            $day = $this->littleEndian ? $first : $second;

            if ($month > 12 && $month <= 31 && $day >= 1 && $day <= 12) {
                [$month, $day] = [$day, $month];
            }

            if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $this->year($yearText, $reference);

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
                'weekday' => EnConstants::WEEKDAYS[strtolower($match['weekday'][0])],
            ]);
            $components->addTag('parser/ENSlashDateParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }

    /**
     * Resolve the year value.
     */
    protected function year(string $year, Reference $reference): int
    {
        if ($year === '') {
            return $reference->date->year;
        }

        return EnConstants::parseYear($year);
    }
}
