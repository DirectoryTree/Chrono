<?php

namespace DirectoryTree\Chrono\Locales\En\Refiners;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\ParsedResult;

trait InteractsWithEnglishRefiners
{
    /**
     * Get the text between two parsed results.
     */
    protected function textBetween(string $text, ParsedResult $current, ParsedResult $next): string
    {
        $afterCurrent = $current->index + strlen($current->text);

        return substr($text, $afterCurrent, $next->index - $afterCurrent);
    }

    /**
     * Determine whether the text connects date and time results.
     */
    protected function isDateTimeConnector(string $between): bool
    {
        return preg_match('/^\s*(?:T|at|after|before|on|of|,|-|\.|∙|:)?\s*$/', $between) === 1;
    }

    /**
     * Resolve an hour with an optional meridiem.
     */
    protected function hourWithMeridiem(int $hour, ?string $meridiem): int
    {
        return match (strtolower($meridiem ?? '')) {
            'am' => $hour === 12 ? 0 : $hour,
            'pm' => $hour < 12 ? $hour + 12 : $hour,
            default => $hour,
        };
    }

    /**
     * @return array<string, int>
     */
    protected function relativeDuration(string $text): array
    {
        preg_match_all('/'.$this->relativeDurationPattern().'/i', $text, $matches, PREG_SET_ORDER);

        $duration = [];

        foreach ($matches as $match) {
            $unit = $this->relativeUnit($match['unit']);
            $amount = ($match['number'] ?? '') !== '' ? (int) $match['number'] : $this->relativeAmount($match['word']);

            $duration[$unit] = ($duration[$unit] ?? 0) + $amount;
        }

        return $duration;
    }

    /**
     * Get the parser pattern.
     */
    protected function relativeDurationPattern(): string
    {
        return '(?:(?<number>\d+)\s*|(?<word>an?|the|one|two|three|four|five|six|seven|eight|nine|ten|few)\s+)(?<unit>seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|weeks?|w|months?|mons?|mos?|mo|quarters?|qtrs?|years?|yrs?|y)\b';
    }

    /**
     * @param  array<string, int>  $duration
     */
    protected function applyDuration(CarbonImmutable $date, array $duration, int $direction): CarbonImmutable
    {
        foreach ($duration as $unit => $amount) {
            $date = $date->add($unit, $amount * $direction);
        }

        return $date;
    }

    /**
     * Resolve the relative duration amount.
     */
    protected function relativeAmount(string $amount): int
    {
        return [
            'a' => 1,
            'an' => 1,
            'the' => 1,
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'ten' => 10,
            'few' => 3,
        ][strtolower($amount)];
    }

    /**
     * Resolve the relative duration unit.
     */
    protected function relativeUnit(string $unit): string
    {
        return match (strtolower($unit)) {
            's', 'sec', 'secs', 'second', 'seconds' => 'second',
            'm', 'min', 'mins', 'minute', 'minutes' => 'minute',
            'h', 'hr', 'hrs', 'hour', 'hours' => 'hour',
            'd', 'day', 'days' => 'day',
            'w', 'week', 'weeks' => 'week',
            'mo', 'mon', 'mons', 'month', 'months' => 'month',
            'qtr', 'qtrs', 'quarter', 'quarters' => 'quarter',
            'y', 'yr', 'yrs', 'year', 'years' => 'year',
            default => strtolower(rtrim($unit, 's')),
        };
    }
}
