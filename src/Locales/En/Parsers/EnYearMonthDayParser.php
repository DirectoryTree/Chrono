<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Locales\En\EnConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class EnYearMonthDayParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Create an English year-month-day parser.
     */
    public function __construct(
        protected readonly bool $strictMonthDateOrder = false,
    ) {}

    /**
     * Parse English year-month-day date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<year>\d{4})[-.\/\s](?:(?<monthname>'.$monthPattern.')\.?|(?<month>\d{1,2}))[-.\/\s](?<day>\d{1,2})(?=\W|$)/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $year = (int) $match['year'][0];
            $month = ($match['monthname'][0] ?? '') !== ''
                ? EnConstants::MONTHS[strtolower($match['monthname'][0])]
                : (int) $match['month'][0];
            $day = (int) $match['day'][0];

            if ($month < 1 || $month > 12) {
                if ($this->strictMonthDateOrder || $day < 1 || $day > 12) {
                    return null;
                }

                [$month, $day] = [$day, $month];
            }

            if ($day < 1 || $day > 31 || ! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

            $components = $this->components($date, [
                'year' => $year,
                'month' => $month,
                'day' => $day,
            ]);
            $components->addTag('parser/ENYearMonthDayParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }
}
