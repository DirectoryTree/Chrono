<?php

namespace Chrono;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Duration;

class ParsedComponents
{
    /**
     * Create parsed date components around a Carbon date.
     *
     * @param  array<string, mixed>  $knownValues
     * @param  array<string, int>  $impliedValues
     * @param  array<int, string>  $tags
     */
    public function __construct(
        protected CarbonImmutable $date,
        protected array $knownValues = [],
        protected array $impliedValues = [],
        protected array $tags = []
    ) {
        $this->impliedValues = array_replace([
            'year' => $this->date->year,
            'month' => $this->date->month,
            'day' => $this->date->day,
            'hour' => 12,
            'minute' => 0,
            'second' => 0,
            'millisecond' => 0,
        ], $this->impliedValues);

        foreach ($this->knownValues as $component => $value) {
            if ($value === true) {
                $this->knownValues[$component] = $this->dateComponentValue($component);
            }
        }

        foreach ($this->impliedValues as $component => $value) {
            if (is_int($value)) {
                $this->date = $this->dateWith($component, $value);
            }
        }

        foreach ($this->knownValues as $component => $value) {
            if (is_int($value)) {
                $this->date = $this->dateWith($component, $value);
            }
        }
    }

    /**
     * Create relative parsing components from a reference and duration.
     *
     * @param  array<string, int|float>  $duration
     */
    public static function createRelativeFromReference(Reference $reference, array $duration = Duration::EMPTY): self
    {
        [$date, $duration] = Duration::addWithNormalizedDuration($reference->date, $duration);
        $components = (new self($reference->date))->addTag('result/relativeDate');

        if (array_intersect(array_keys($duration), ['hour', 'minute', 'second', 'millisecond']) !== []) {
            $components->addTag('result/relativeDateAndTime');
            Dates::assignSimilarTime($components, $date);
            Dates::assignSimilarDate($components, $date);
            $components->assign('timezoneOffset', $reference->date->offsetMinutes);

            return $components;
        }

        Dates::implySimilarTime($components, $date);
        $components->imply('timezoneOffset', $reference->date->offsetMinutes);

        if (array_key_exists('day', $duration)) {
            Dates::assignSimilarDate($components, $date);
            $components->assign('weekday', $date->dayOfWeek);

            return $components;
        }

        if (array_key_exists('week', $duration)) {
            Dates::assignSimilarDate($components, $date);
            $components->imply('weekday', $date->dayOfWeek);

            return $components;
        }

        $components->imply('day', $date->day);

        if (array_key_exists('month', $duration)) {
            $components->assign('month', $date->month);
            $components->assign('year', $date->year);

            return $components;
        }

        $components->imply('month', $date->month);

        array_key_exists('year', $duration)
            ? $components->assign('year', $date->year)
            : $components->imply('year', $date->year);

        return $components;
    }

    /**
     * Get the Carbon date represented by these components.
     */
    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    /**
     * Get a known or implied component value.
     */
    public function get(string $component): int|string|Meridiem|null
    {
        return match ($component) {
            'year' => $this->componentValue('year'),
            'month' => $this->componentValue('month'),
            'day' => $this->componentValue('day'),
            'hour' => $this->componentValue('hour'),
            'minute' => $this->componentValue('minute'),
            'second' => $this->componentValue('second'),
            'millisecond' => $this->componentValue('millisecond'),
            'weekday' => $this->componentValue('weekday'),
            'meridiem' => $this->meridiemValue(),
            'timezoneOffset' => $this->componentValue('timezoneOffset'),
            default => null,
        };
    }

    /**
     * Assign a certain component value.
     */
    public function assign(string $component, int $value): self
    {
        $this->knownValues[$component] = $value;
        unset($this->impliedValues[$component]);

        $this->date = $this->dateWith($component, $value);

        return $this;
    }

    /**
     * Assign an implied component value when it is not already certain.
     */
    public function imply(string $component, int $value): self
    {
        if ($this->isCertain($component)) {
            return $this;
        }

        $this->impliedValues[$component] = $value;
        $this->date = $this->dateWith($component, $value);

        return $this;
    }

    /**
     * Determine whether the component was explicitly assigned.
     */
    public function isCertain(string $component): bool
    {
        return array_key_exists($component, $this->knownValues);
    }

    /**
     * Get the names of the explicitly assigned components.
     *
     * @return array<int, string>
     */
    public function getCertainComponents(): array
    {
        return array_keys($this->knownValues);
    }

    /**
     * Delete known and implied component values.
     *
     * @param  string|array<int, string>  $components
     */
    public function delete(string|array $components): self
    {
        foreach ((array) $components as $component) {
            unset($this->knownValues[$component]);
            unset($this->impliedValues[$component]);
        }

        return $this;
    }

    /**
     * Shift the component date and mark shifted date/time values as implied.
     *
     * @param  array<string, int|float>  $duration
     */
    public function addDurationAsImplied(array $duration): self
    {
        [$date, $duration] = Duration::addWithNormalizedDuration($this->date, $duration);

        if (array_intersect(array_keys($duration), ['day', 'week', 'month', 'year']) !== []) {
            $this->delete(['day', 'weekday', 'month', 'year']);
            $this->date = $date;
            $this->imply('day', $date->day);
            $this->imply('weekday', $date->dayOfWeek);
            $this->imply('month', $date->month);
            $this->imply('year', $date->year);
        }

        if (array_intersect(array_keys($duration), ['second', 'minute', 'hour']) !== []) {
            $this->delete(['second', 'minute', 'hour']);
            $this->date = $date;
            $this->imply('second', $date->second);
            $this->imply('minute', $date->minute);
            $this->imply('hour', $date->hour);
        }

        return $this;
    }

    /**
     * Clone the component set.
     */
    public function clone(): self
    {
        return new self($this->date, $this->knownValues, $this->impliedValues);
    }

    /**
     * Determine whether the result only has date certainty.
     */
    public function isOnlyDate(): bool
    {
        return ! $this->isCertain('hour')
            && ! $this->isCertain('minute')
            && ! $this->isCertain('second');
    }

    /**
     * Determine whether the result only has time certainty.
     */
    public function isOnlyTime(): bool
    {
        return ! $this->isCertain('weekday')
            && ! $this->isCertain('day')
            && ! $this->isCertain('month')
            && ! $this->isCertain('year');
    }

    /**
     * Determine whether the result only has weekday certainty.
     */
    public function isOnlyWeekdayComponent(): bool
    {
        return $this->isCertain('weekday')
            && ! $this->isCertain('day')
            && ! $this->isCertain('month');
    }

    /**
     * Determine whether the result has a month/day without a certain year.
     */
    public function isDateWithUnknownYear(): bool
    {
        return $this->isCertain('month')
            && ! $this->isCertain('year');
    }

    /**
     * Determine whether the represented date/time components are valid.
     */
    public function isValidDate(): bool
    {
        $year = $this->componentValue('year');
        $month = $this->componentValue('month');
        $day = $this->componentValue('day');
        $hour = $this->componentValue('hour');
        $minute = $this->componentValue('minute');
        $second = $this->componentValue('second');
        $millisecond = $this->componentValue('millisecond');

        if ($year === null || $month === null || $day === null) {
            return false;
        }

        if (($hour !== null && ($hour < 0 || $hour >= 24))
            || ($minute !== null && ($minute < 0 || $minute >= 60))
            || ($second !== null && ($second < 0 || $second >= 60))
            || ($millisecond !== null && $millisecond < 0)) {
            return false;
        }

        return $month >= 1
            && $month <= 12
            && $day >= 1
            && $day <= $this->daysInMonth($year, $month);
    }

    /**
     * Get the certain timezone offset in minutes.
     */
    public function timezoneOffset(): ?int
    {
        return $this->isCertain('timezoneOffset') ? (int) ($this->date->offsetMinutes) : null;
    }

    /**
     * Add a parser or refiner tag.
     */
    public function addTag(string $tag): self
    {
        if (! in_array($tag, $this->tags, true)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Add parser or refiner tags.
     *
     * @param  iterable<string>  $tags
     */
    public function addTags(iterable $tags): self
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * Get parser and refiner tags attached to the components.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return $this->tags;
    }

    /**
     * Convert the components to an upstream-style debug string.
     */
    public function __toString(): string
    {
        $tags = $this->tags;
        sort($tags);

        return sprintf(
            '[ParsedComponents {tags: %s, knownValues: %s, impliedValues: %s}]',
            json_encode($tags, JSON_UNESCAPED_SLASHES),
            json_encode($this->knownValues, JSON_UNESCAPED_SLASHES),
            json_encode($this->impliedValues, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function dateWith(string $component, int $value): CarbonImmutable
    {
        return match ($component) {
            'year' => $this->date->year($value),
            'month' => $this->date->month($value),
            'day' => $this->date->day($value),
            'hour' => $this->date->hour($value),
            'minute' => $this->date->minute($value),
            'second' => $this->date->second($value),
            'millisecond' => $this->date->millisecond($value),
            'timezoneOffset' => $this->date->shiftTimezone($this->timezoneNameFromOffset($value)),
            default => $this->date,
        };
    }

    protected function dateComponentValue(string $component): int|bool
    {
        return match ($component) {
            'year' => $this->date->year,
            'month' => $this->date->month,
            'day' => $this->date->day,
            'hour' => $this->date->hour,
            'minute' => $this->date->minute,
            'second' => $this->date->second,
            'millisecond' => $this->date->millisecond,
            'weekday' => $this->date->dayOfWeek,
            'timezoneOffset' => $this->date->offsetMinutes,
            default => true,
        };
    }

    protected function componentValue(string $component): ?int
    {
        if (isset($this->knownValues[$component]) && is_int($this->knownValues[$component])) {
            return $this->knownValues[$component];
        }

        if (isset($this->impliedValues[$component])) {
            return $this->impliedValues[$component];
        }

        return null;
    }

    protected function meridiemValue(): ?Meridiem
    {
        $value = $this->componentValue('meridiem');

        if ($value !== null) {
            return Meridiem::from($value);
        }

        return null;
    }

    protected function timezoneNameFromOffset(int $offset): string
    {
        $sign = $offset < 0 ? '-' : '+';
        $offset = abs($offset);

        return sprintf('%s%02d:%02d', $sign, intdiv($offset, 60), $offset % 60);
    }

    protected function daysInMonth(int $year, int $month): int
    {
        if ($month === 2) {
            return $this->isLeapYear($year) ? 29 : 28;
        }

        return in_array($month, [4, 6, 9, 11], true) ? 30 : 31;
    }

    protected function isLeapYear(int $year): bool
    {
        return $year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0);
    }
}
