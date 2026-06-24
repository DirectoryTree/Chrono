<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

class EnMergeTrailingTimeRangeRefiner implements Refiner
{
    use InteractsWithEnglishRefiners;

    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        return array_map(function (ParsedResult $result) use ($text, $results): ParsedResult {
            if ($result->end !== null || ! $result->start->isCertain('day') || ! $result->start->isCertain('hour')) {
                return $result;
            }

            $remaining = substr($text, $result->index + strlen($result->text));

            if (preg_match('/^(?<range>\s*(?:-|to|until|through|thru|till)\s*(?<hour>\d{1,2})(?::(?<minute>\d{2}))?(?::(?<second>\d{2}))?\s*(?<meridiem>am|pm)?)(?!\s*\/)(?=\W|$)/i', $remaining, $match, PREG_OFFSET_CAPTURE) !== 1) {
                return $result;
            }

            $resultEnd = $result->index + strlen($result->text);
            $rangeEnd = $resultEnd + strlen($match['range'][0]);
            $endpointIndex = $resultEnd + $match['hour'][1];

            foreach ($results as $candidate) {
                if ($candidate === $result) {
                    continue;
                }

                if ($candidate->index === $endpointIndex && $candidate->index + strlen($candidate->text) > $rangeEnd) {
                    return $result;
                }
            }

            $hour = $this->hourWithMeridiem((int) $match['hour'][0], ($match['meridiem'][0] ?? '') !== '' ? $match['meridiem'][0] : null);
            $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;
            $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;

            if ($hour > 23 || $minute > 59 || $second > 59) {
                return $result;
            }

            $date = $result->start->date()
                ->hour($hour)
                ->minute($minute)
                ->second($second);

            $range = new ParsedResult(
                $result->index,
                $result->text.$match['range'][0],
                $result->start,
                new ParsedComponents($date, [
                    'year' => true,
                    'month' => true,
                    'day' => true,
                    'hour' => true,
                    'minute' => true,
                    'second' => true,
                ]),
                $result->tags(),
            );

            $range->addTag('refiner/mergeTrailingTimeRange');

            return $range;
        }, $results);
    }
}
