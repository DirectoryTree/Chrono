<?php

namespace Chrono\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

class ExtractTimezoneOffsetRefiner implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        foreach ($results as $result) {
            if ($result->start->isCertain('timezoneOffset')) {
                continue;
            }

            $suffix = substr($text, $result->index + strlen($result->text));

            if (preg_match('/^\s*(?:(?:\((?:GMT|UTC)?\s*([+-])(\d{1,2})(?::?(\d{2}))?\))|(?:(?:GMT|UTC)\s*)?([+-])(\d{1,2})(?::?(\d{2}))?)(?!\s*(?:seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|weeks?|w|months?|mons?|mos?|mo|quarters?|qtrs?|years?|yrs?|y)\b)/i', $suffix, $match) !== 1) {
                continue;
            }

            $sign = $match[1] !== '' ? $match[1] : $match[4];
            $hour = $match[2] !== '' ? $match[2] : $match[5];
            $minute = $match[3] !== '' ? $match[3] : ($match[6] ?? 0);
            $offset = ((int) $hour * 60) + (int) $minute;

            if ($offset > 14 * 60) {
                continue;
            }

            $this->assignTimezone($result, $sign === '-' ? -$offset : $offset);
            $result->text .= $match[0];
        }

        return $results;
    }

    /**
     * Assign the parsed component value.
     */
    protected function assignTimezone(ParsedResult $result, int $offset): void
    {
        $result->start->assign('timezoneOffset', $offset);
        $result->end?->assign('timezoneOffset', $offset);
    }
}
