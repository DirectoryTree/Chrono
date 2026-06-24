<?php

namespace Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\De\CreatesParsedComponents;
use Chrono\Locales\De\DeConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class DeMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse German month-name date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $weekday = DeConstants::weekdayPattern();
        $month = DeConstants::monthPattern();
        $results = [
            ...$this->parseSameMonthRanges($text, $reference, $month),
            ...$this->parseCrossMonthRanges($text, $reference, $month),
        ];

        preg_match_all('/\b(?:(?:am\s+)?(?:(?<weekday>'.$weekday.')\.?,?\s*(?:den\s+)?)?)?(?<day>\d{1,2})\.\s*(?<month>'.$month.')\.?(?:\s+(?<year>\d{1,4})(?!:)(?:\s*(?<era>v\.?\s*chr\.?|n\.?\s*chr\.?|v\.?\s*c\.?|n\.?\s*c\.?|v\.?\s*u\.?\s*z\.?|n\.?\s*u\.?\s*z\.?|u\.?\s*z\.?|d\.?\s*g\.?\s*z\.?|v\.?\s*d\.?\s*z\.?|n\.?\s*d\.?\s*z\.?|v\.?\s*d\.?\s*g\.?\s*z\.?|n\.?\s*d\.?\s*g\.?\s*z\.?))?)?(?:\s+(?:um\s+)?(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?:uhr)?)?(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $results = [
            ...$results,
            ...array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
                $day = (int) $match['day'][0];
                $month = $this->month($match['month'][0]);
                $yearText = $match['year'][0] ?? '';
                $year = $yearText !== ''
                    ? $this->year((int) $yearText, $match['era'][0] ?? '')
                    : $this->closestYear($reference, $month, $day);

                if (! checkdate($month, $day, max(1, abs($year)))) {
                    return null;
                }

                $hour = ($match['hour'][0] ?? '') !== '' ? (int) $match['hour'][0] : 12;
                $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;

                if ($hour > 23 || $minute > 59) {
                    return null;
                }

                $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);
                $known = [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    ...(($match['hour'][0] ?? '') !== '' ? ['hour' => $hour, 'minute' => $minute] : []),
                ];

                if (($match['weekday'][0] ?? '') !== '') {
                    $known['weekday'] = $this->weekday($match['weekday'][0]);
                }

                $components = $this->components($date, $known);
                $components->addTag('parser/DEMonthNameParser');

                return new ParsedResult($match[0][1], trim($match[0][0]), $components);
            }, $matches))),
        ];

        usort($results, fn (ParsedResult $a, ParsedResult $b) => $a->index <=> $b->index ?: strlen($b->text) <=> strlen($a->text));

        return $results;
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseSameMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})\.\s*(?:-|bis)\s*(?<endDay>\d{1,2})\.\s*(?<month>'.$monthPattern.')\.?(?:\s+(?<year>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $startDay = (int) $match['startDay'][0];
            $endDay = (int) $match['endDay'][0];
            $month = $this->month($match['month'][0]);
            $year = ($match['year'][0] ?? '') !== ''
                ? $this->year((int) $match['year'][0], '')
                : $this->closestYear($reference, $month, $startDay);

            if (! checkdate($month, $startDay, max(1, abs($year))) || ! checkdate($month, $endDay, max(1, abs($year)))) {
                return null;
            }

            $start = CarbonImmutable::create($year, $month, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $month, $endDay, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult(
                $match[0][1],
                trim($match[0][0]),
                $this->components($start, ['year' => $year, 'month' => $month, 'day' => $startDay]),
                $this->components($end, ['year' => $year, 'month' => $month, 'day' => $endDay]),
            );
        }, $matches)));
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseCrossMonthRanges(string $text, Reference $reference, string $monthPattern): array
    {
        preg_match_all('/\b(?<startDay>\d{1,2})\.\s*(?<startMonth>'.$monthPattern.')\.?\s*(?:-|bis)\s*(?<endDay>\d{1,2})\.\s*(?<endMonth>'.$monthPattern.')\.?(?:\s+(?<year>\d{1,4}))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $startDay = (int) $match['startDay'][0];
            $endDay = (int) $match['endDay'][0];
            $startMonth = $this->month($match['startMonth'][0]);
            $endMonth = $this->month($match['endMonth'][0]);
            $year = ($match['year'][0] ?? '') !== ''
                ? $this->year((int) $match['year'][0], '')
                : $this->closestYear($reference, $startMonth, $startDay);

            if (! checkdate($startMonth, $startDay, max(1, abs($year))) || ! checkdate($endMonth, $endDay, max(1, abs($year)))) {
                return null;
            }

            $start = CarbonImmutable::create($year, $startMonth, $startDay, 12, 0, 0, $reference->date->timezone);
            $end = CarbonImmutable::create($year, $endMonth, $endDay, 12, 0, 0, $reference->date->timezone);

            if (($match['year'][0] ?? '') === '' && $end->lessThan($start)) {
                $end = $end->addYear();
            }

            return new ParsedResult(
                $match[0][1],
                trim($match[0][0]),
                $this->components($start, ['year' => $start->year, 'month' => $startMonth, 'day' => $startDay]),
                $this->components($end, ['year' => $end->year, 'month' => $endMonth, 'day' => $endDay]),
            );
        }, $matches)));
    }

    protected function month(string $month): int
    {
        return DeConstants::monthNumber($month);
    }

    protected function weekday(string $weekday): int
    {
        return DeConstants::weekdayNumber($weekday);
    }

    protected function closestYear(Reference $reference, int $month, int $day): int
    {
        $year = $reference->date->year;
        $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

        if ($date->diffInDays($reference->date, false) > 180) {
            return $year + 1;
        }

        if ($date->diffInDays($reference->date, false) < -180) {
            return $year - 1;
        }

        return $year;
    }

    protected function year(int $year, string $era): int
    {
        $era = $this->normalize($era);

        if ($era !== '') {
            return str_starts_with($era, 'v') ? -$year : $year;
        }

        return $year < 100 && $year > 50 ? 1900 + $year : $year;
    }

    protected function normalize(string $value): string
    {
        return DeConstants::normalize($value);
    }
}
