<?php

namespace Chrono\Locales\It\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\It\CreatesParsedComponents;
use Chrono\Locales\It\ItConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class ItCasualYearMonthDayParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Italian casual year-month-day dates.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = ItConstants::monthPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?<year>[0-9]{4})[.\\/\\s](?:(?<monthName>{$monthPattern})|(?<month>[0-9]{1,2}))[.\\/\\s](?<day>[0-9]{1,2})(?=\\W|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = ($match['month'][0] ?? '') !== ''
                ? (int) $match['month'][0]
                : ItConstants::monthNumber($match['monthName'][0]);

            if ($month === null || $month < 1 || $month > 12) {
                return null;
            }

            $year = (int) $match['year'][0];
            $day = (int) $match['day'][0];

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

            $components = $this->components($date, [
                'year' => $year,
                'month' => $month,
                'day' => $day,
            ]);
            $components->addTag('parser/ITCasualYearMonthDayParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }
}
