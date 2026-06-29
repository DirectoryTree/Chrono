<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

readonly class EnMergeRelativeAfterDateRefiner extends MergingRefiner
{
    use InteractsWithEnglishRefiners;

    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $relative, string $text, Reference $reference, Options $options): bool
    {
        if (preg_match('/^\s*$/', $textBetween) !== 1) {
            return false;
        }

        return $this->isSignedRelativeResult($relative);
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $date, ParsedResult $relative, string $text, Reference $reference, Options $options): ParsedResult
    {
        $duration = $this->relativeDuration($relative->text);
        $direction = str_starts_with(trim($relative->text), '-') ? -1 : 1;
        $resultDate = $this->applyDuration($date->start->date(), $duration, $direction);

        $date->start
            ->assign('year', $resultDate->year)
            ->assign('month', $resultDate->month)
            ->assign('day', $resultDate->day)
            ->assign('hour', $resultDate->hour)
            ->assign('minute', $resultDate->minute)
            ->assign('second', $resultDate->second);

        $date->text .= $textBetween.$relative->text;
        $date->addTag('refiner/mergeRelativeAfterDate');

        return $date;
    }

    /**
     * Determine whether the result is a signed relative result.
     */
    protected function isSignedRelativeResult(ParsedResult $result): bool
    {
        return in_array('result/relativeDate', $result->tags(), true)
            && preg_match('/^[+-]/', trim($result->text)) === 1
            && $this->relativeDuration($result->text) !== [];
    }
}
