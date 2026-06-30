<?php

namespace DirectoryTree\Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Locales\De\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class DeDashDateParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse German numeric dash and dotted date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $weekday = 'sonntag|so|montag|mo|dienstag|di|mittwoch|mi|donnerstag|do|freitag|fr|samstag|sa';

        preg_match_all('/\b(?:(?<weekday>'.$weekday.')\s+)?(?<day>\d{1,2})[.-](?<month>\d{1,2})(?:[.-](?<year>\d{2,4}))?(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference, $options): ?ParsedResult {
            $day = (int) $match['day'][0];
            $month = (int) $match['month'][0];
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? $this->year((int) $yearText) : $reference->date->year;

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

            if ($yearText === '' && $options->forwardDate() && $date->lessThan($reference->date)) {
                $date = $date->addYear();
                $year = $date->year;
            }

            $components = $this->components($date, [
                ...($yearText !== '' || $options->forwardDate() ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
                ...(($match['weekday'][0] ?? '') !== '' ? ['weekday' => $date->dayOfWeek] : []),
            ]);
            $components->addTag('parser/DEDashDateParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches)));
    }

    /**
     * Resolve the year value.
     */
    protected function year(int $year): int
    {
        if ($year < 100) {
            return $year > 50 ? 1900 + $year : 2000 + $year;
        }

        return $year;
    }
}
