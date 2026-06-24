<?php

namespace Chrono\Locales\It\Refiners;

use Chrono\Locales\It\Parsers\InteractsWithItalianRelativeDates;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

class ItMergeRelativeDateRefiner extends MergingRefiner
{
    use InteractsWithItalianRelativeDates;

    /**
     * Determine if a relative duration should be re-based on a following absolute date.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $relative, ParsedResult $date, string $text, Reference $reference, Options $options): bool
    {
        return preg_match('/^\s*$/', $textBetween) === 1
            && in_array('result/relativeDate', $relative->tags(), true)
            && $this->hasImpliedReferenceDate($relative)
            && $date->start->isCertain('day')
            && $date->start->isCertain('month')
            && $date->start->isCertain('year')
            && $this->duration($relative->text) !== [];
    }

    /**
     * Re-base a relative duration on its following absolute date.
     */
    protected function mergeResults(string $textBetween, ParsedResult $relative, ParsedResult $date, string $text, Reference $reference, Options $options): ParsedResult
    {
        $duration = $this->duration($relative->text);
        $direction = $this->hasImpliedEarlierReferenceDate($relative) ? -1 : 1;
        $resultDate = $this->applyDuration($date->start->date(), $duration, $direction);
        $unit = $this->mostSpecificUnit($duration);

        $result = new ParsedResult($relative->index, substr($text, $relative->index, $date->index + strlen($date->text) - $relative->index), $this->relativeComponents(
            $resultDate,
            $this->certainComponents($resultDate, $unit),
        ), null, $relative->tags());

        $result->addTag('refiner/mergeRelativeDateReference');

        return $result;
    }

    /**
     * Determine if the relative phrase implies an earlier reference date.
     */
    protected function hasImpliedEarlierReferenceDate(ParsedResult $result): bool
    {
        return preg_match('/\s+(?:prima|dal)$/iu', $result->text) === 1;
    }

    /**
     * Determine if the relative phrase implies a later reference date.
     */
    protected function hasImpliedLaterReferenceDate(ParsedResult $result): bool
    {
        return preg_match('/\s+(?:dopo|dal|fino)$/iu', $result->text) === 1;
    }

    /**
     * Determine if the relative phrase carries an explicit reference direction.
     */
    protected function hasImpliedReferenceDate(ParsedResult $result): bool
    {
        return $this->hasImpliedEarlierReferenceDate($result)
            || $this->hasImpliedLaterReferenceDate($result);
    }
}
