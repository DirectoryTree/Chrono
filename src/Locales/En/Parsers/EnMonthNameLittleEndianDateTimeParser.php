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

readonly class EnMonthNameLittleEndianDateTimeParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<day>\d{1,2})(?:st|nd|rd|th)?(?:\s+of)?\s+(?<month>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{1,4})(?:\s*(?<era>BCE|CE|BC|AD|BE))?)?\s*,?\s+(?:at\s+)?(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?<meridiem>a\.?m\.?|p\.?m\.?)?(?<oclock>\s*o\W*clock)?(?=\W|$)/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            if (($match['minute'][0] ?? '') === '' && ($match['meridiem'][0] ?? '') === '' && trim($match['oclock'][0] ?? '') === '') {
                return null;
            }

            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $day = (int) $match['day'][0];
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? $this->year((int) $yearText, $match['era'][0] ?? '') : Years::findYearClosestToReference($reference->date, $day, $month);
            $hour = $this->meridiemHour((int) $match['hour'][0], $this->normalizeMeridiem($match['meridiem'][0] ?? ''));
            $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;

            if (! checkdate($month, $day, max(1, abs($year))) || $hour > 23 || $minute > 59) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'minute' => $minute,
            ]));
        }, $matches)));
    }

    /**
     * Normalize the value.
     */
    protected function normalizeMeridiem(string $meridiem): ?string
    {
        $meridiem = strtolower(str_replace('.', '', $meridiem));

        return $meridiem !== '' ? $meridiem : null;
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
