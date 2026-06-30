<?php

namespace DirectoryTree\Chrono\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiner;
use DirectoryTree\Chrono\Timezone;

readonly class ExtractTimezoneAbbrRefiner implements Refiner
{
    /**
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        foreach ($results as $result) {
            $suffix = substr($text, $result->index + strlen($result->text));

            if (preg_match('/^\s*,?\s*\(?([A-Z]{2,4})\)?(?=\W|$)/i', $suffix, $match) !== 1) {
                continue;
            }

            $rawAbbr = $match[1];
            $abbr = strtoupper($rawAbbr);
            $offset = Timezone::offset($abbr, $result->start->date(), $options);

            if ($offset === null) {
                continue;
            }

            $currentOffset = $result->start->get('timezoneOffset');

            if ($currentOffset !== null && $currentOffset !== $offset && $result->start->isCertain('timezoneOffset')) {
                continue;
            }

            if ($currentOffset !== null && $currentOffset !== $offset && $abbr !== $rawAbbr) {
                continue;
            }

            if (! $result->start->isCertain('hour') && $abbr !== $rawAbbr) {
                continue;
            }

            if (! $result->start->isCertain('timezoneOffset')) {
                $result->start->assign('timezoneOffset', $offset);
            }

            if ($result->end !== null && ! $result->end->isCertain('timezoneOffset')) {
                $result->end->assign('timezoneOffset', $offset);
            }

            $result->text .= $match[0];
        }

        return $results;
    }
}
