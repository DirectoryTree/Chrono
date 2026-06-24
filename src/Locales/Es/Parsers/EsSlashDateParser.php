<?php

namespace Chrono\Locales\Es\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Es\CreatesParsedComponents;
use Chrono\Locales\Es\EsConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Reference;

class EsSlashDateParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Spanish numeric slash date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $results = (new SlashDateFormatParser(
            littleEndian: true,
            includeYearWhenForwardDate: true,
            twoDigitYearPastThreshold: 50,
        ))->parse($text, $reference, $options);

        $weekday = EsConstants::weekdayPattern();

        preg_match_all('/\b(?:(?<weekday>'.$weekday.')\s+)?(?<day>\d{1,2})\/(?<month>\d{1,2})(?:\/(?<year>\d{2,4}))?(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $results = [
            ...$results,
            ...array_values(array_filter(array_map(function (array $match) use ($reference, $options): ?ParsedResult {
                if (($match['weekday'][0] ?? '') === '') {
                    return null;
                }

                $day = (int) $match['day'][0];
                $month = (int) $match['month'][0];
                $year = ($match['year'][0] ?? '') !== ''
                    ? $this->year((int) $match['year'][0])
                    : $reference->date->year;

                if (! checkdate($month, $day, $year)) {
                    return null;
                }

                $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

                if (($match['year'][0] ?? '') === '' && $options->forwardDate() && $date->lessThan($reference->date)) {
                    $date = $date->addYear();
                    $year = $date->year;
                }

                $known = [
                    'month' => $month,
                    'day' => $day,
                    ...((($match['year'][0] ?? '') !== '' || $options->forwardDate()) ? ['year' => $year] : []),
                ];

                if (($match['weekday'][0] ?? '') !== '') {
                    $known['weekday'] = $date->dayOfWeek;
                }

                $components = $this->components($date, $known);
                $components->addTag('parser/ESSlashDateParser');

                return new ParsedResult($match[0][1], $match[0][0], $components);
            }, $matches))),
        ];

        usort($results, fn (ParsedResult $a, ParsedResult $b) => $a->index <=> $b->index ?: strlen($b->text) <=> strlen($a->text));

        return $results;
    }

    protected function year(int $year): int
    {
        if ($year < 100) {
            return $year > 50 ? 1900 + $year : 2000 + $year;
        }

        return $year;
    }
}
