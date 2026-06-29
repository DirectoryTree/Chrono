<?php

namespace Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Weekdays;
use Chrono\Locales\De\DeConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class DeWeekdayParser implements Parser
{
    /**
     * Parse German weekday expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $results = [
            ...$this->parseWeekdayRanges($text, $reference, $options),
            ...$this->parseSingleWeekdays($text, $reference, $options),
        ];

        usort($results, fn (ParsedResult $a, ParsedResult $b) => $a->index <=> $b->index ?: strlen($b->text) <=> strlen($a->text));

        return $results;
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseSingleWeekdays(string $text, Reference $reference, Options $options): array
    {
        $weekday = $this->weekdayPattern();

        preg_match_all('/\b(?:am\s+)?(?:(?<modifier>letzte[rsn]?|diese[rsn]?|n(?:ä|ae|a)chste[rsn]?)\s+)?(?<weekday>'.$weekday.')(?:\s+(?<postmodifier>n(?:ä|ae|a)chste\s+woche))?\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_map(function (array $match) use ($reference, $options): ParsedResult {
            $weekday = $this->weekday($match['weekday'][0]);
            $modifier = $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''));
            $date = $reference->date->addDays($this->daysToWeekday($reference, $weekday, $modifier, $options));

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($date, $weekday),
            );
        }, $matches);
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseWeekdayRanges(string $text, Reference $reference, Options $options): array
    {
        $weekday = $this->weekdayPattern();

        preg_match_all('/\b(?:(?<startModifier>letzte[rsn]?|diese[rsn]?|n(?:ä|ae|a)chste[rsn]?)\s+)?(?<start>'.$weekday.')\s*(?:-|bis)\s*(?:(?<endModifier>letzte[rsn]?|diese[rsn]?|n(?:ä|ae|a)chste[rsn]?)\s+)?(?<end>'.$weekday.')\b/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_map(function (array $match) use ($reference, $options): ParsedResult {
            $startWeekday = $this->weekday($match['start'][0]);
            $endWeekday = $this->weekday($match['end'][0]);
            $startModifier = $this->modifier($match['startModifier'][0] ?? '');
            $endModifier = $this->modifier($match['endModifier'][0] ?? '');
            $start = $reference->date->addDays($this->daysToWeekday($reference, $startWeekday, $startModifier, $options));
            $end = $reference->date->addDays($this->daysToWeekday($reference, $endWeekday, $endModifier, $options));

            if ($end->lessThanOrEqualTo($start)) {
                $end = $end->addWeek();
            }

            return new ParsedResult(
                $match[0][1],
                $match[0][0],
                $this->components($start, $startWeekday),
                $this->components($end, $endWeekday),
            );
        }, $matches);
    }

    /**
     * Resolve the weekday value.
     */
    protected function daysToWeekday(Reference $reference, int $weekday, ?string $modifier = null, ?Options $options = null): int
    {
        if ($modifier === null && ($options?->forwardDate() ?? false)) {
            $modifier = 'next';
        }

        return Weekdays::getDaysToWeekday($reference->date, $weekday, $modifier);
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function components(CarbonImmutable $date, int $weekday): ParsedComponents
    {
        $components = new ParsedComponents($date->setTime(12, 0, 0)->millisecond(0), []);

        $components
            ->imply('year', $date->year)
            ->imply('month', $date->month)
            ->imply('day', $date->day)
            ->assign('weekday', $weekday)
            ->addTag('parser/DEWeekdayParser');

        return $components;
    }

    /**
     * Resolve the weekday value.
     */
    protected function weekday(string $weekday): int
    {
        return DeConstants::weekdayNumber($weekday);
    }

    /**
     * Get modifier.
     */
    protected function modifier(string $modifier): ?string
    {
        $modifier = $this->normalize($modifier);

        return match (true) {
            str_starts_with($modifier, 'letzt') => 'last',
            str_starts_with($modifier, 'dies') => 'this',
            str_starts_with($modifier, 'nachste'), str_starts_with($modifier, 'naechste') => 'next',
            default => null,
        };
    }

    /**
     * Get the parser pattern.
     */
    protected function weekdayPattern(): string
    {
        return DeConstants::weekdayPattern();
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return DeConstants::normalize($value);
    }
}
