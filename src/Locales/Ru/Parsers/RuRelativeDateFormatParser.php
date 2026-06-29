<?php

namespace Chrono\Locales\Ru\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Ru\RuConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class RuRelativeDateFormatParser implements Parser
{
    use InteractsWithRussianRelativeDates;

    /**
     * Parse Russian relative unit expressions like "в прошлом месяце".
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $unitPattern = RuConstants::timeUnitPattern();

        preg_match_all("/(?<![\\pL\\pN])(?<modifier>в\\s+прошл(?:ый|ом)|на\\s+прошл(?:ой|ую)|в\\s+следующ(?:ий|ем)|на\\s+следующ(?:ей|ую)|в\\s+эт(?:от|ом)|на\\s+эт(?:ой|у))\\s+(?<unit>{$unitPattern})(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $unit = RuConstants::TIME_UNITS[mb_strtolower($match['unit'][0])] ?? null;

            if ($unit === null) {
                return null;
            }

            $modifier = mb_strtolower($match['modifier'][0]);
            $direction = str_contains($modifier, 'прошл') ? -1 : (str_contains($modifier, 'следующ') ? 1 : 0);
            $date = $this->relativeDate($reference, $unit, $direction);
            $components = $this->relativeComponents($date, $this->certainComponents($date, $this->mostSpecificRelativeUnit($unit)))
                ->addTag('parser/RURelativeDateFormatParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components, null, ['result/relativeDate']);
        }, $matches)));
    }

    /**
     * Apply the relative shift and normalize coarse units to their start.
     */
    protected function relativeDate(Reference $reference, string $unit, int $direction): CarbonImmutable
    {
        $date = $direction === 0 ? $reference->date : $reference->date->add($unit, $direction);

        return match ($unit) {
            'week' => $date,
            'month' => $date->day(1),
            'quarter' => $date->month(((int) floor(($date->month - 1) / 3) * 3) + 1)->day(1),
            'year' => $direction === 0 ? $date->month(1)->day(1) : $date,
            default => $date,
        };
    }

    /**
     * Collapse quarter certainty to month-level certainty.
     */
    protected function mostSpecificRelativeUnit(string $unit): string
    {
        return $unit === 'quarter' ? 'month' : $unit;
    }
}
