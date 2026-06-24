<?php

namespace Chrono\Locales\Ja\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Ja\CreatesParsedComponents;
use Chrono\Locales\Ja\JaConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class JaSlashDateFormatParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Japanese big-endian slash dates.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\d０-９])(?:(?<year>[0-9０-９]{4})[\/／])?(?<month>[0-1０-１]?[0-9０-９])[\/／](?<day>[0-3０-３]?[0-9０-９])(?=\W|の|$)/u', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = (int) JaConstants::toHankaku($match['month'][0]);
            $day = (int) JaConstants::toHankaku($match['day'][0]);

            if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? (int) JaConstants::toHankaku($yearText) : JaConstants::closestYear($reference, $day, $month);

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ])->addTag('parser/JPSlashDateFormatParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }
}
