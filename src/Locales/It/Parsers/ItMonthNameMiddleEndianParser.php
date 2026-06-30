<?php

namespace DirectoryTree\Chrono\Locales\It\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;
use DirectoryTree\Chrono\Locales\It\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\It\ItConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class ItMonthNameMiddleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Italian month-day and month-day-range expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $ordinalPattern = ItConstants::ordinalPattern();
        $monthPattern = ItConstants::monthPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?<month>{$monthPattern})(?:-|\\/|\\s*,?\\s*)(?<day>{$ordinalPattern})(?!\\s*(?:am|pm))\\s*(?:(?:al|-|alle|del|\\s)\\s*(?<endday>{$ordinalPattern})\\s*)?(?:(?:-|\\/|\\s*,?\\s*)(?<year>[1-2][0-9]{3}|[5-9][0-9]))?(?=\\W|$)(?!\\:\\d)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = ItConstants::monthNumber($match['month'][0]);
            $day = ItConstants::ordinalNumber($match['day'][0]);

            if ($month === null || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? Years::findMostLikelyADYear((int) $yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ]);
            $components->addTag('parser/ITMonthNameMiddleEndianParser');

            return new ParsedResult($match[0][1], $match[0][0], $components, $end);
        }, $matches)));
    }

    /**
     * Build end components for same-month ranges.
     */
    protected function endComponents(array $match, CarbonImmutable $start, int $month): ?ParsedComponents
    {
        $endDayText = $match['endday'][0] ?? '';

        if ($endDayText === '') {
            return null;
        }

        $endDay = ItConstants::ordinalNumber($endDayText);

        if (! checkdate($month, $endDay, $start->year)) {
            return null;
        }

        return $this->components($start->day($endDay), [
            'month' => $month,
            'day' => $endDay,
        ]);
    }
}
