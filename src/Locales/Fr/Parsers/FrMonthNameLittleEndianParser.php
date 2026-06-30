<?php

namespace DirectoryTree\Chrono\Locales\Fr\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;
use DirectoryTree\Chrono\Locales\Fr\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\Fr\FrConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class FrMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse French day-month and same-month day-range expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = FrConstants::monthPattern();
        $yearPattern = '[1-9][0-9]{0,3}\s*(?:AC|AD|p\.?\s*C(?:hr?)?\.?\s*n\.?)|[1-2][0-9]{3}|[5-9][0-9]';

        preg_match_all(
            "/(?<![\\pL\\pN])(?<day>[0-9]{1,2}(?:er)?)(?:\\s*(?:au|-|–|jusqu'au?|\\s)\\s*(?<endday>[0-9]{1,2}(?:er)?))?(?:-|\\/|\\s*(?:de)?\\s*)(?<month>{$monthPattern})(?:(?:-|\\/|,?\\s*)(?<year>{$yearPattern}(?![^\\s]\\d)))?(?=\\W|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $day = $this->ordinal($match['day'][0]);

            if ($day > 31) {
                return null;
            }

            $month = $this->month($match['month'][0]);
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? $this->year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if (! checkdate($month, $day, max(1, abs($year)))) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $known = [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ];

            return new ParsedResult(
                $match[0][1],
                trim($match[0][0]),
                $this->components($date, $known),
                $this->endComponents($match, $date, $known),
            );
        }, $matches)));
    }

    /**
     * Build end components for same-month ranges.
     *
     * @param  array<string, int>  $startKnown
     */
    protected function endComponents(array $match, CarbonImmutable $start, array $startKnown): ?ParsedComponents
    {
        $endDayText = $match['endday'][0] ?? '';

        if ($endDayText === '') {
            return null;
        }

        $endDay = $this->ordinal($endDayText);

        if (! checkdate($start->month, $endDay, max(1, abs($start->year)))) {
            return null;
        }

        return $this->components($start->day($endDay), [
            ...$startKnown,
            'day' => $endDay,
        ]);
    }

    /**
     * Get ordinal.
     */
    protected function ordinal(string $value): int
    {
        return (int) preg_replace('/er$/iu', '', $value);
    }

    /**
     * Resolve the month value.
     */
    protected function month(string $month): int
    {
        return FrConstants::monthNumber($month);
    }

    /**
     * Resolve the year value.
     */
    protected function year(string $year): int
    {
        $normalized = $this->normalize($year);
        $number = (int) preg_replace('/[^0-9]+/u', '', $year);

        if (str_contains($normalized, 'ac')) {
            return -$number;
        }

        if (str_contains($normalized, 'ad') || str_contains($normalized, 'chr') || str_contains($normalized, 'c')) {
            return $number;
        }

        return $number < 100 ? ($number > 50 ? $number + 1900 : $number + 2000) : $number;
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return FrConstants::normalize($value);
    }
}
