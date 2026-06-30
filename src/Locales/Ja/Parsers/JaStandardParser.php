<?php

namespace DirectoryTree\Chrono\Locales\Ja\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Locales\Ja\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\Ja\JaConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class JaStandardParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Japanese standard year-month-day expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?:(?:(?<special>[同今本])|(?<yearfull>(?<era>昭和|平成|令和)?(?<year>[0-9０-９]{1,4}|元)))年\s*)?(?<month>[0-9０-９]{1,2})月\s*(?<day>[0-9０-９]{1,2})日/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = (int) JaConstants::toHankaku($match['month'][0]);
            $day = (int) JaConstants::toHankaku($match['day'][0]);

            if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return null;
            }

            $yearText = $match['yearfull'][0] ?? '';
            $year = $this->year($match, $reference, $day, $month);

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $components = $this->components($date, [
                ...($yearText !== '' || ($match['special'][0] ?? '') !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ])->addTag('parser/JPStandardParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }

    /**
     * Resolve the year value.
     */
    protected function year(array $match, Reference $reference, int $day, int $month): int
    {
        if (($match['special'][0] ?? '') !== '') {
            return $reference->date->year;
        }

        if (($match['yearfull'][0] ?? '') === '') {
            return JaConstants::closestYear($reference, $day, $month);
        }

        $year = ($match['year'][0] ?? '') === '元' ? 1 : (int) JaConstants::toHankaku($match['year'][0]);

        return match ($match['era'][0] ?? '') {
            '令和' => $year + 2018,
            '平成' => $year + 1988,
            '昭和' => $year + 1925,
            default => $year,
        };
    }
}
