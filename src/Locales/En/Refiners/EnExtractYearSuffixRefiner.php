<?php

namespace DirectoryTree\Chrono\Locales\En\Refiners;

use DirectoryTree\Chrono\Locales\En\EnConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiner;

readonly class EnExtractYearSuffixRefiner implements Refiner
{
    /**
     * Extract explicit year suffixes into date results with unknown years.
     *
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array
    {
        foreach ($results as $result) {
            if (! $result->start->isDateWithUnknownYear()) {
                continue;
            }

            $suffix = substr($text, $result->index + strlen($result->text));

            if (preg_match('/^\s*('.EnConstants::YEAR_PATTERN.')/i', $suffix, $match) !== 1) {
                continue;
            }

            if (strlen(trim($match[0])) <= 3) {
                continue;
            }

            $year = EnConstants::parseYear($match[1]);
            $result->start->assign('year', $year);
            $result->end?->assign('year', $year);
            $result->text .= $match[0];
            $result->addTag('refiner/extractYearSuffix');
        }

        return $results;
    }
}
