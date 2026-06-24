<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

class EnMergeRelativeAfterDateRefiner extends MergingRefiner
{
    use InteractsWithEnglishRefiners;

    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $relative, string $text, Reference $reference, Options $options): bool
    {
        if (preg_match('/^\s*$/', $textBetween) !== 1) {
            return false;
        }

        return $this->isSignedRelativeResult($relative);
    }

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

    protected function isSignedRelativeResult(ParsedResult $result): bool
    {
        return in_array('result/relativeDate', $result->tags(), true)
            && preg_match('/^[+-]/', trim($result->text)) === 1
            && $this->relativeDuration($result->text) !== [];
    }
}
