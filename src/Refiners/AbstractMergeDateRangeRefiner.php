<?php

namespace Chrono\Refiners;

use Chrono\Dates;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;

abstract class AbstractMergeDateRangeRefiner extends MergingRefiner
{
    /**
     * Get the connector pattern allowed between date range endpoints.
     */
    abstract protected function patternBetween(): string;

    /**
     * Determine if two adjacent date results form a date range.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $start, ParsedResult $end, string $text, Reference $reference, Options $options): bool
    {
        if ($start->end !== null || $end->end !== null) {
            return false;
        }

        return preg_match($this->patternBetween(), $textBetween) === 1;
    }

    /**
     * Merge two date results into a single range result.
     */
    protected function mergeResults(string $textBetween, ParsedResult $start, ParsedResult $end, string $text, Reference $reference, Options $options): ParsedResult
    {
        $this->implyMissingComponents($start->start, $end->start);
        [$start, $end] = $this->orderedRange($start, $end);

        $rangeStart = min($start->index, $end->index);
        $rangeEnd = max($start->index + strlen($start->text), $end->index + strlen($end->text));

        $range = new ParsedResult(
            $rangeStart,
            substr($text, $rangeStart, $rangeEnd - $rangeStart),
            $start->start,
            $end->start,
            $start->tags(),
        );

        $range->addTags($end->tags());
        $range->addTag('refiner/mergeDateRange');

        return $range;
    }

    /**
     * Imply missing component values.
     */
    protected function implyMissingComponents(ParsedComponents $start, ParsedComponents $end): void
    {
        if ($start->isOnlyWeekdayComponent() || $end->isOnlyWeekdayComponent()) {
            return;
        }

        foreach ($end->getCertainComponents() as $component) {
            if (! $start->isCertain($component)) {
                $start->imply($component, (int) $end->get($component));
            }
        }

        foreach ($start->getCertainComponents() as $component) {
            if (! $end->isCertain($component)) {
                $end->imply($component, (int) $start->get($component));
            }
        }
    }

    /**
     * @return array{0: ParsedResult, 1: ParsedResult}
     */
    protected function orderedRange(ParsedResult $start, ParsedResult $end): array
    {
        if (! $start->start->date()->greaterThan($end->start->date())) {
            return [$start, $end];
        }

        if ($end->start->isOnlyWeekdayComponent() && $end->start->date()->addWeek()->greaterThan($start->start->date())) {
            Dates::implySimilarDate($end->start, $end->start->date()->addWeek());

            return [$start, $end];
        }

        if ($start->start->isOnlyWeekdayComponent() && $start->start->date()->subWeek()->lessThan($end->start->date())) {
            Dates::implySimilarDate($start->start, $start->start->date()->subWeek());

            return [$start, $end];
        }

        if ($end->start->isDateWithUnknownYear() && $end->start->date()->addYear()->greaterThan($start->start->date())) {
            $end->start->imply('year', $end->start->date()->addYear()->year);

            return [$start, $end];
        }

        if ($start->start->isDateWithUnknownYear() && $start->start->date()->subYear()->lessThan($end->start->date())) {
            $start->start->imply('year', $start->start->date()->subYear()->year);

            return [$start, $end];
        }

        return [$end, $start];
    }
}
