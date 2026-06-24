<?php

namespace Chrono\Locales\Uk\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\Uk\UkConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class UkRelativeDateFormatParser implements Parser
{
    use InteractsWithUkrainianRelativeDates;

    /**
     * Parse Ukrainian relative unit expressions like "у наступному місяці".
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $unitPattern = UkConstants::timeUnitPattern();

        preg_match_all("/(?<![\\pL\\pN])(?<modifier>в\\s+минулому|у\\s+минулому|на\\s+минулому|минулого|на\\s+наступному|в\\s+наступному|у\\s+наступному|наступного|на\\s+цьому|в\\s+цьому|у\\s+цьому|цього)\\s*(?<unit>{$unitPattern})(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $unit = UkConstants::TIME_UNITS[mb_strtolower($match['unit'][0])] ?? null;

            if ($unit === null) {
                return null;
            }

            $modifier = mb_strtolower($match['modifier'][0]);
            $direction = str_contains($modifier, 'минул') ? -1 : (str_contains($modifier, 'наступ') ? 1 : 0);
            $date = $this->relativeDate($reference, $unit, $direction);
            $components = $this->relativeComponents($date, $this->certainComponents($date, $this->mostSpecificRelativeUnit($unit)))
                ->addTag('parser/UKRelativeDateFormatParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components, null, ['result/relativeDate']);
        }, $matches)));
    }

    /**
     * Apply the relative shift and normalize coarse units to their start.
     */
    protected function relativeDate(Reference $reference, string $unit, int $direction): CarbonImmutable
    {
        if ($direction !== 0) {
            return $reference->date->add($unit, $direction);
        }

        return match ($unit) {
            'week' => $reference->date->subDays($reference->date->dayOfWeek),
            'month' => $reference->date->day(1),
            'quarter' => $reference->date->firstOfQuarter(),
            'year' => $reference->date->month(1)->day(1),
            default => $reference->date,
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
