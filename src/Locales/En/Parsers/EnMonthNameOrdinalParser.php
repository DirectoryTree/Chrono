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

readonly class EnMonthNameOrdinalParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();
        $ordinalPattern = $this->ordinalWordPattern();

        return [
            ...$this->parseMiddleEndianOrdinalWordDates($text, $reference, $monthPattern, $ordinalPattern),
            ...$this->parseLittleEndianOrdinalWordDates($text, $reference, $monthPattern, $ordinalPattern),
            ...$this->parseMiddleEndianOrdinalWordRanges($text, $reference, $monthPattern, $ordinalPattern),
            ...$this->parseLittleEndianOrdinalWordRanges($text, $reference, $monthPattern, $ordinalPattern),
        ];
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMiddleEndianOrdinalWordDates(string $text, Reference $reference, string $monthPattern, string $ordinalPattern): array
    {
        preg_match_all('/\b(?<month>'.$monthPattern.')\.?\s+(?<day>'.$ordinalPattern.')(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            return $this->monthDateResult($match, $reference, $match['month'][0], (string) $this->ordinalWord($match['day'][0]), $match['year'][0] ?? '', $match['era'][0] ?? '');
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseLittleEndianOrdinalWordDates(string $text, Reference $reference, string $monthPattern, string $ordinalPattern): array
    {
        preg_match_all('/\b(?<day>'.$ordinalPattern.')(?:\s+of)?\s+(?<month>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            return $this->monthDateResult($match, $reference, $match['month'][0], (string) $this->ordinalWord($match['day'][0]), $match['year'][0] ?? '', $match['era'][0] ?? '');
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMiddleEndianOrdinalWordRanges(string $text, Reference $reference, string $monthPattern, string $ordinalPattern): array
    {
        preg_match_all('/\b(?<month>'.$monthPattern.')\.?\s+(?<startday>'.$ordinalPattern.')\s*(?:-|to|until|through|till)\s*(?<endday>'.$ordinalPattern.')(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return $this->ordinalWordRangeResults($matches, $reference);
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseLittleEndianOrdinalWordRanges(string $text, Reference $reference, string $monthPattern, string $ordinalPattern): array
    {
        preg_match_all('/\b(?<startday>'.$ordinalPattern.')\s*(?:-|to|until|through|till)\s*(?<endday>'.$ordinalPattern.')\s+(?<month>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return $this->ordinalWordRangeResults($matches, $reference);
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

        return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
            ...($yearText !== '' ? ['year' => $year] : []),
            'month' => $month,
            'day' => $day,
        ]));
    }

    /**
     * @param  array<int, array<string, array{0: string, 1: int}>>  $matches
     * @return array<int, ParsedResult>
     */
    protected function ordinalWordRangeResults(array $matches, Reference $reference): array
    {
        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $startDay = $this->ordinalWord($match['startday'][0]);
            $endDay = $this->ordinalWord($match['endday'][0]);
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? $this->year((int) $yearText, $match['era'][0] ?? '') : Years::findYearClosestToReference($reference->date, $startDay, $month);

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

    /**
     * Get the parser pattern.
     */
    protected function ordinalWordPattern(): string
    {
        return implode('|', [
            'thirty[\s-]*first',
            'twenty[\s-]*(?:first|second|third|fourth|fifth|sixth|seventh|eighth|ninth)',
            'twentieth',
            'nineteenth',
            'eighteenth',
            'seventeenth',
            'sixteenth',
            'fifteenth',
            'fourteenth',
            'thirteenth',
            'twelfth',
            'eleventh',
            'tenth',
            'ninth',
            'eighth',
            'seventh',
            'sixth',
            'fifth',
            'fourth',
            'third',
            'second',
            'first',
            'thirtieth',
        ]);
    }

    /**
     * Resolve the ordinal word value.
     */
    protected function ordinalWord(string $word): int
    {
        return $this->ordinalWords()[$this->normalizeOrdinalWord($word)];
    }

    /**
     * @return array<string, int>
     */
    protected function ordinalWords(): array
    {
        return [
            'first' => 1,
            'second' => 2,
            'third' => 3,
            'fourth' => 4,
            'fifth' => 5,
            'sixth' => 6,
            'seventh' => 7,
            'eighth' => 8,
            'ninth' => 9,
            'tenth' => 10,
            'eleventh' => 11,
            'twelfth' => 12,
            'thirteenth' => 13,
            'fourteenth' => 14,
            'fifteenth' => 15,
            'sixteenth' => 16,
            'seventeenth' => 17,
            'eighteenth' => 18,
            'nineteenth' => 19,
            'twentieth' => 20,
            'twentyfirst' => 21,
            'twentysecond' => 22,
            'twentythird' => 23,
            'twentyfourth' => 24,
            'twentyfifth' => 25,
            'twentysixth' => 26,
            'twentyseventh' => 27,
            'twentyeighth' => 28,
            'twentyninth' => 29,
            'thirtieth' => 30,
            'thirtyfirst' => 31,
        ];
    }

    /**
     * Normalize the value.
     */
    protected function normalizeOrdinalWord(string $word): string
    {
        return str_replace(['-', ' '], '', strtolower($word));
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
