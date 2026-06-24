<?php

namespace Chrono\Locales\Ja\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

class JaMergeWeekdayComponentRefiner extends MergingRefiner
{
    protected function shouldMergeResults(string $textBetween, ParsedResult $date, ParsedResult $weekday, string $text, Reference $reference, Options $options): bool
    {
        return $date->start->isCertain('day')
            && $weekday->start->isOnlyWeekdayComponent()
            && ! $weekday->start->isCertain('hour')
            && preg_match('/^[,、の]?\s*$/u', $textBetween) === 1;
    }

    protected function mergeResults(string $textBetween, ParsedResult $date, ParsedResult $weekday, string $text, Reference $reference, Options $options): ParsedResult
    {
        $date->start->assign('weekday', (int) $weekday->start->get('weekday'));
        $date->end?->assign('weekday', (int) $weekday->start->get('weekday'));
        $date->text .= $textBetween.$weekday->text;
        $date->addTag('refiner/mergeWeekdayComponent');

        return $date;
    }
}
