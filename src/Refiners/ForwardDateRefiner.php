<?php

namespace Chrono\Refiners;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Duration;
use Chrono\Calculation\Weekdays;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

class ForwardDateRefiner implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        if (! $options->forwardDate()) {
            return $results;
        }

        foreach ($results as $result) {
            $this->forwardOnlyTime($result, $reference);
            $this->forwardOnlyWeekday($result, $reference);
            $this->forwardDateWithUnknownYear($result, $reference);
        }

        return $results;
    }

    /**
     * Move time-only results to the following day when they are behind the reference.
     */
    protected function forwardOnlyTime(ParsedResult $result, Reference $reference): void
    {
        if (! $result->start->isOnlyTime() || ! $reference->date->greaterThan($result->start->date())) {
            return;
        }

        $date = Duration::add($reference->date, ['day' => 1]);
        $this->implyDate($result->start, $date);

        if ($result->end !== null && $result->end->isOnlyTime()) {
            $this->implyDate($result->end, $date);

            if ($result->start->date()->greaterThan($result->end->date())) {
                $this->implyDate($result->end, Duration::add($date, ['day' => 1]));
            }
        }
    }

    /**
     * Move weekday-only results forward from the reference date.
     */
    protected function forwardOnlyWeekday(ParsedResult $result, Reference $reference): void
    {
        if (! $result->start->isOnlyWeekdayComponent() || ! $reference->date->greaterThan($result->start->date())) {
            return;
        }

        $daysToAdd = Weekdays::getDaysForwardToWeekday($reference->date, (int) $result->start->get('weekday')) ?: 7;
        $date = Duration::add($reference->date, ['day' => $daysToAdd]);
        $this->implyDate($result->start, $date);

        if ($result->end !== null && $result->start->date()->greaterThan($result->end->date())) {
            $daysToAdd = Weekdays::getDaysForwardToWeekday($reference->date, (int) $result->start->get('weekday')) ?: 7;
            $this->implyDate($result->end, Duration::add($reference->date, ['day' => $daysToAdd]));
        }
    }

    /**
     * Move month/day results without certain years into the future.
     */
    protected function forwardDateWithUnknownYear(ParsedResult $result, Reference $reference): void
    {
        if (! $result->start->isDateWithUnknownYear() || ! $reference->date->greaterThan($result->start->date())) {
            return;
        }

        for ($i = 0; $i < 3 && $reference->date->greaterThan($result->start->date()); $i++) {
            $result->start->imply('year', (int) $result->start->get('year') + 1);

            if ($result->end !== null && ! $result->end->isCertain('year')) {
                $result->end->imply('year', (int) $result->end->get('year') + 1);
            }
        }
    }

    /**
     * Imply the date components from the given Carbon date.
     */
    protected function implyDate(ParsedComponents $components, CarbonImmutable $date): void
    {
        $components
            ->imply('year', $date->year)
            ->imply('month', $date->month)
            ->imply('day', $date->day);
    }
}
